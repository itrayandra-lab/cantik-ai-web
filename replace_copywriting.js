/**
 * Script untuk mengganti copywriting dari index_local_backup.html ke index.html
 * Menggunakan regex-based text node replacement
 * Hanya mengganti teks konten, tidak mengubah CSS/class/atribut/struktur
 */

const fs = require('fs');

// Baca kedua file
const sourceFile = 'index_local_backup.html'; // copywriting baru (Cantik.AI)
const targetFile = 'index.html';              // file yang akan diupdate

let source = fs.readFileSync(sourceFile, 'utf8');
let target = fs.readFileSync(targetFile, 'utf8');

/**
 * Ekstrak semua text nodes dari HTML string
 * Returns array of {text, context} objects
 */
function extractTextNodes(html) {
  const results = [];
  // Match text content between tags (excluding script, style, comments)
  // This regex finds text between > and < that isn't inside script/style
  const regex = />([^<]+)</g;
  let match;
  while ((match = regex.exec(html)) !== null) {
    const text = match[1];
    const trimmed = text.trim();
    if (trimmed && trimmed.length > 0) {
      results.push({
        full: match[0],
        text: text,
        trimmed: trimmed,
        index: match.index
      });
    }
  }
  return results;
}

/**
 * Ekstrak pasangan teks dari kedua file berdasarkan posisi relatif
 * Menggunakan pendekatan line-by-line comparison
 */
function buildReplacementMap(sourceHtml, targetHtml) {
  const sourceLines = sourceHtml.split('\n');
  const targetLines = targetHtml.split('\n');
  
  const replacements = [];
  
  // Kedua file memiliki struktur yang sama, jadi kita bisa compare line by line
  const minLen = Math.min(sourceLines.length, targetLines.length);
  
  for (let i = 0; i < minLen; i++) {
    const srcLine = sourceLines[i];
    const tgtLine = targetLines[i];
    
    // Skip jika baris sama persis
    if (srcLine === tgtLine) continue;
    
    // Skip baris yang berisi CSS/JS/script/style
    if (isCodeLine(srcLine) || isCodeLine(tgtLine)) continue;
    
    // Ekstrak teks dari kedua baris
    const srcTexts = extractInlineTexts(srcLine);
    const tgtTexts = extractInlineTexts(tgtLine);
    
    // Jika jumlah text nodes sama, buat mapping
    if (srcTexts.length === tgtTexts.length && srcTexts.length > 0) {
      for (let j = 0; j < srcTexts.length; j++) {
        const srcText = srcTexts[j].trim();
        const tgtText = tgtTexts[j].trim();
        
        if (srcText !== tgtText && srcText.length > 0 && tgtText.length > 0) {
          replacements.push({
            from: tgtTexts[j],
            to: srcTexts[j],
            line: i + 1
          });
        }
      }
    }
  }
  
  return replacements;
}

function isCodeLine(line) {
  const trimmed = line.trim();
  // Skip script/style content, comments, empty lines
  if (!trimmed) return true;
  if (trimmed.startsWith('//') || trimmed.startsWith('/*') || trimmed.startsWith('*')) return true;
  if (trimmed.startsWith('<script') || trimmed.startsWith('</script')) return true;
  if (trimmed.startsWith('<style') || trimmed.startsWith('</style')) return true;
  if (trimmed.startsWith('<!--') || trimmed.startsWith('-->')) return true;
  // Skip lines that look like CSS
  if (trimmed.includes('{') && trimmed.includes('}')) return true;
  if (trimmed.endsWith('{') || trimmed.endsWith('}')) return true;
  if (trimmed.includes(':') && (trimmed.endsWith(';') || trimmed.endsWith('{'))) return true;
  // Skip lines with JS-like content
  if (trimmed.includes('function') || trimmed.includes('var ') || trimmed.includes('const ') || trimmed.includes('let ')) return true;
  if (trimmed.startsWith('(') || trimmed.startsWith(')')) return true;
  return false;
}

function extractInlineTexts(line) {
  const texts = [];
  const regex = />([^<>]+)</g;
  let match;
  while ((match = regex.exec(line)) !== null) {
    const text = match[1];
    if (text.trim()) {
      texts.push(text);
    }
  }
  return texts;
}

// Build replacement map
console.log('Building replacement map...');
const replacements = buildReplacementMap(source, target);
console.log(`Found ${replacements.length} text differences`);

// Apply replacements to target
let updatedTarget = target;
let appliedCount = 0;

// Sort by length descending to avoid partial replacements
replacements.sort((a, b) => b.from.length - a.from.length);

// Deduplicate
const seen = new Set();
const uniqueReplacements = replacements.filter(r => {
  const key = r.from + '|||' + r.to;
  if (seen.has(key)) return false;
  seen.add(key);
  return true;
});

console.log(`Unique replacements: ${uniqueReplacements.length}`);

for (const rep of uniqueReplacements) {
  // Only replace text content between tags, not inside attributes
  // Use a careful replacement that preserves surrounding whitespace
  const fromEscaped = rep.from.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
  
  // Match the text between > and < 
  const pattern = new RegExp(`(>)${fromEscaped}(<)`, 'g');
  const newContent = updatedTarget.replace(pattern, `$1${rep.to}$2`);
  
  if (newContent !== updatedTarget) {
    updatedTarget = newContent;
    appliedCount++;
    console.log(`  ✓ Line ${rep.line}: "${rep.from.trim()}" → "${rep.to.trim()}"`);
  }
}

console.log(`\nApplied ${appliedCount} replacements`);

// Save updated file
fs.writeFileSync(targetFile, updatedTarget, 'utf8');
console.log(`\nSaved to ${targetFile}`);
