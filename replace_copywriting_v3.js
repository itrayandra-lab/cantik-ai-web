/**
 * Script v3: Mengganti copywriting dari index_local_backup.html ke index.html
 * Pendekatan: Line-by-line comparison dengan sliding window untuk handle offset
 * Fokus pada body section saja
 */

const fs = require('fs');

const sourceFile = 'index_local_backup.html';
const targetFile = 'index.html';

const sourceLines = fs.readFileSync(sourceFile, 'utf8').split('\n');
const targetLines = fs.readFileSync(targetFile, 'utf8').split('\n');

// Backup
fs.writeFileSync(targetFile + '.bak2', targetLines.join('\n'), 'utf8');
console.log('Backup created');

// Find body start in both files
function findBodyStart(lines) {
  for (let i = 0; i < lines.length; i++) {
    if (lines[i].trim() === '<body>') return i;
  }
  return 0;
}

const srcBodyStart = findBodyStart(sourceLines);
const tgtBodyStart = findBodyStart(targetLines);

console.log(`Source body starts at line: ${srcBodyStart + 1}`);
console.log(`Target body starts at line: ${tgtBodyStart + 1}`);

/**
 * Check if a line contains only CSS/JS content (not HTML text)
 */
function isNonTextLine(line) {
  const t = line.trim();
  if (!t) return true;
  
  // Pure CSS properties
  if (/^[a-z-]+\s*:\s*.+;$/.test(t)) return true;
  if (/^[a-z-]+\s*:\s*.+\s*!important;$/.test(t)) return true;
  
  // CSS selectors and blocks
  if (/^[.#@]/.test(t) && (t.endsWith('{') || t.endsWith('}'))) return true;
  if (t === '{' || t === '}' || t === '};') return true;
  
  // JS code patterns
  if (/^(var|let|const|function|if|for|while|return|import|export)\s/.test(t)) return true;
  if (/^\(function/.test(t)) return true;
  if (/^\/\//.test(t)) return true;
  if (/^\/\*/.test(t)) return true;
  if (/^\*/.test(t)) return true;
  
  // HTML comments
  if (t.startsWith('<!--') || t.startsWith('-->')) return true;
  
  // Pure HTML tags (no text content)
  if (/^<[^>]+>$/.test(t)) return true;
  if (/^<\/[^>]+>$/.test(t)) return true;
  
  return false;
}

/**
 * Extract text content from an HTML line
 * Returns the text between > and < or null if no text
 */
function extractLineText(line) {
  // Match text between > and < 
  const matches = [];
  const regex = />([^<]+)</g;
  let m;
  while ((m = regex.exec(line)) !== null) {
    const text = m[1].trim();
    if (text) matches.push(text);
  }
  return matches;
}

/**
 * Check if line has meaningful text content
 */
function hasTextContent(line) {
  const texts = extractLineText(line);
  return texts.length > 0 && texts.some(t => t.length > 0);
}

/**
 * Get the "structural signature" of a line (tags without text content)
 */
function getLineSignature(line) {
  // Remove text content, keep only tags and attributes
  return line.replace(/>([^<]+)</g, '><').trim();
}

// Build a map of text replacements
// Strategy: find lines in source body that have text content,
// find corresponding lines in target body with same structure,
// map the text differences

const replacements = new Map(); // target_text -> source_text

// We'll use a smarter approach: find all text-containing lines in both bodies
// and match them by their structural signature

const srcTextLines = [];
const tgtTextLines = [];

for (let i = srcBodyStart; i < sourceLines.length; i++) {
  const line = sourceLines[i];
  if (hasTextContent(line) && !isNonTextLine(line)) {
    srcTextLines.push({ line, lineNum: i + 1, texts: extractLineText(line), sig: getLineSignature(line) });
  }
}

for (let i = tgtBodyStart; i < targetLines.length; i++) {
  const line = targetLines[i];
  if (hasTextContent(line) && !isNonTextLine(line)) {
    tgtTextLines.push({ line, lineNum: i + 1, texts: extractLineText(line), sig: getLineSignature(line) });
  }
}

console.log(`Source text lines in body: ${srcTextLines.length}`);
console.log(`Target text lines in body: ${tgtTextLines.length}`);

// Match by signature
const sigMap = new Map(); // signature -> source texts
for (const srcEntry of srcTextLines) {
  if (!sigMap.has(srcEntry.sig)) {
    sigMap.set(srcEntry.sig, []);
  }
  sigMap.get(srcEntry.sig).push(srcEntry.texts);
}

// For each target text line, find matching source by signature
let matchedCount = 0;
let diffCount = 0;

for (const tgtEntry of tgtTextLines) {
  const srcMatches = sigMap.get(tgtEntry.sig);
  if (srcMatches && srcMatches.length > 0) {
    const srcTexts = srcMatches[0]; // Take first match
    const tgtTexts = tgtEntry.texts;
    
    if (srcTexts.length === tgtTexts.length) {
      for (let j = 0; j < srcTexts.length; j++) {
        if (srcTexts[j] !== tgtTexts[j] && tgtTexts[j].length > 0) {
          replacements.set(tgtTexts[j], srcTexts[j]);
          diffCount++;
        }
      }
      matchedCount++;
    }
  }
}

console.log(`Matched lines: ${matchedCount}`);
console.log(`Text differences found: ${diffCount}`);
console.log(`Unique replacements: ${replacements.size}`);

// Show all replacements
console.log('\n=== REPLACEMENTS ===');
for (const [from, to] of replacements) {
  console.log(`  FROM: "${from}"`);
  console.log(`  TO:   "${to}"`);
  console.log('');
}

// Apply replacements to target file
let updatedContent = targetLines.join('\n');
let appliedCount = 0;

// Sort by length descending to avoid partial replacements
const sortedReps = Array.from(replacements.entries())
  .sort((a, b) => b[0].length - a[0].length);

for (const [fromText, toText] of sortedReps) {
  if (fromText === toText) continue;
  
  const escaped = fromText.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
  
  // Replace text between > and < tags
  const pattern = new RegExp(`(>)(\\s*)${escaped}(\\s*)(<)`, 'g');
  const newContent = updatedContent.replace(pattern, `$1$2${toText}$3$4`);
  
  if (newContent !== updatedContent) {
    updatedContent = newContent;
    appliedCount++;
    console.log(`✓ Applied: "${fromText.substring(0, 50)}" → "${toText.substring(0, 50)}"`);
  } else {
    console.log(`✗ Not found in HTML: "${fromText.substring(0, 50)}"`);
  }
}

console.log(`\nTotal applied: ${appliedCount}`);

fs.writeFileSync(targetFile, updatedContent, 'utf8');
console.log(`Saved to ${targetFile}`);
