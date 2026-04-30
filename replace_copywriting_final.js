/**
 * Script FINAL: Mengganti copywriting dari index_local_backup.html ke index.html
 * 
 * Strategi:
 * 1. Parse kedua file, skip semua script/style blocks
 * 2. Extract semua text nodes dari HTML (teks antara tag)
 * 3. Match berdasarkan posisi relatif (index ke-N dari semua text nodes)
 * 4. Buat replacement map
 * 5. Apply ke target file
 */

const fs = require('fs');

const sourceFile = 'index_local_backup.html';
const targetFile = 'index.html';

const sourceContent = fs.readFileSync(sourceFile, 'utf8');
const targetContent = fs.readFileSync(targetFile, 'utf8');

// Backup
fs.writeFileSync(targetFile + '.backup', targetContent, 'utf8');
console.log('Backup created: index.html.backup');

/**
 * Remove script and style blocks, replace with placeholder spaces
 */
function removeScriptStyle(html) {
  return html
    .replace(/<script[\s\S]*?<\/script>/gi, match => '\x00'.repeat(match.length))
    .replace(/<style[\s\S]*?<\/style>/gi, match => '\x00'.repeat(match.length))
    .replace(/<!--[\s\S]*?-->/g, match => '\x00'.repeat(match.length));
}

/**
 * Extract all text nodes from HTML
 * Returns array of {text, trimmed, originalIndex}
 * where originalIndex is the position in the ORIGINAL html string
 */
function extractTextNodes(html) {
  const cleaned = removeScriptStyle(html);
  const nodes = [];
  
  // Find text between > and <
  let i = 0;
  while (i < cleaned.length) {
    if (cleaned[i] === '>') {
      // Find next <
      let j = i + 1;
      while (j < cleaned.length && cleaned[j] !== '<') {
        j++;
      }
      
      if (j > i + 1) {
        const text = html.substring(i + 1, j); // Use original html for actual text
        const trimmed = text.trim();
        
        // Skip if it's null bytes (was script/style) or empty
        if (trimmed && !trimmed.includes('\x00') && trimmed.length > 0) {
          // Skip if it looks like CSS or JS
          if (!looksLikeCode(trimmed)) {
            nodes.push({
              text: text,
              trimmed: trimmed,
              start: i + 1,
              end: j
            });
          }
        }
      }
      i = j;
    } else {
      i++;
    }
  }
  
  return nodes;
}

function looksLikeCode(text) {
  // Skip CSS-like content
  if (/^[a-z-]+\s*:\s*.+;$/.test(text)) return true;
  if (/^\{/.test(text) || /^\}/.test(text)) return true;
  // Skip JS-like content  
  if (/^(var|let|const|function|if|for|return)\s/.test(text)) return true;
  if (/^\/\//.test(text) || /^\/\*/.test(text)) return true;
  // Skip pure numbers or special chars
  if (/^[\d\s.,%-]+$/.test(text) && text.length < 3) return true;
  return false;
}

console.log('Extracting text nodes...');
const sourceNodes = extractTextNodes(sourceContent);
const targetNodes = extractTextNodes(targetContent);

console.log(`Source text nodes: ${sourceNodes.length}`);
console.log(`Target text nodes: ${targetNodes.length}`);

// Build replacement map by positional matching
const replacements = new Map(); // trimmed_target -> trimmed_source

const minLen = Math.min(sourceNodes.length, targetNodes.length);
let diffCount = 0;

for (let i = 0; i < minLen; i++) {
  const src = sourceNodes[i];
  const tgt = targetNodes[i];
  
  if (src.trimmed !== tgt.trimmed) {
    diffCount++;
    if (!replacements.has(tgt.trimmed)) {
      replacements.set(tgt.trimmed, src.trimmed);
    }
  }
}

console.log(`Differences found: ${diffCount}`);
console.log(`Unique replacements: ${replacements.size}`);

// Show all replacements
console.log('\n=== ALL REPLACEMENTS ===');
let idx = 0;
for (const [from, to] of replacements) {
  idx++;
  console.log(`${idx}. FROM: "${from.substring(0, 80)}"`);
  console.log(`   TO:   "${to.substring(0, 80)}"`);
}

// Apply replacements to target content
let updatedContent = targetContent;
let appliedCount = 0;
let failedCount = 0;

// Sort by length descending to avoid partial replacements
const sortedReps = Array.from(replacements.entries())
  .sort((a, b) => b[0].length - a[0].length);

for (const [fromText, toText] of sortedReps) {
  if (fromText === toText) continue;
  
  // Escape special regex chars
  const escaped = fromText.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
  
  // Replace text between > and < (with optional surrounding whitespace/newlines)
  // Pattern: >  TEXT  < where TEXT is our target
  const pattern = new RegExp(`(>)([ \\t\\r\\n]*)${escaped}([ \\t\\r\\n]*)(<)`, 'g');
  
  const newContent = updatedContent.replace(pattern, (match, gt, ws1, ws2, lt) => {
    return `${gt}${ws1}${toText}${ws2}${lt}`;
  });
  
  if (newContent !== updatedContent) {
    updatedContent = newContent;
    appliedCount++;
    console.log(`✓ "${fromText.substring(0, 60)}" → "${toText.substring(0, 60)}"`);
  } else {
    failedCount++;
    console.log(`✗ NOT FOUND: "${fromText.substring(0, 60)}"`);
  }
}

console.log(`\n=== SUMMARY ===`);
console.log(`Applied: ${appliedCount}`);
console.log(`Failed: ${failedCount}`);

// Save
fs.writeFileSync(targetFile, updatedContent, 'utf8');
console.log(`\nSaved to ${targetFile}`);
