/**
 * Script v2: Mengganti copywriting dari index_local_backup.html ke index.html
 * Pendekatan: Parse HTML body section, extract text nodes, buat mapping berdasarkan
 * struktur tag yang sama (tag name + class + position)
 */

const fs = require('fs');

const sourceFile = 'index_local_backup.html';
const targetFile = 'index.html';

let source = fs.readFileSync(sourceFile, 'utf8');
let target = fs.readFileSync(targetFile, 'utf8');

// Backup target
fs.writeFileSync(targetFile + '.bak', target, 'utf8');
console.log('Backup created: index.html.bak');

/**
 * Extract body section only (from <body> to </body>)
 */
function extractBody(html) {
  const bodyStart = html.indexOf('<body');
  const bodyEnd = html.lastIndexOf('</body>') + 7;
  return html.substring(bodyStart, bodyEnd);
}

/**
 * Extract all text nodes with their surrounding context
 * Returns array of {text, before, after, fullMatch}
 * Skips script and style content
 */
function extractTextNodes(html) {
  const nodes = [];
  
  // Remove script and style blocks first for analysis
  let cleanHtml = html
    .replace(/<script[\s\S]*?<\/script>/gi, (m) => ' '.repeat(m.length))
    .replace(/<style[\s\S]*?<\/style>/gi, (m) => ' '.repeat(m.length))
    .replace(/<!--[\s\S]*?-->/g, (m) => ' '.repeat(m.length));
  
  // Find all text between tags
  const regex = /(>)([ \t]*[^<\n\r]+?[ \t]*)(<)/g;
  let match;
  
  while ((match = regex.exec(cleanHtml)) !== null) {
    const text = match[2];
    const trimmed = text.trim();
    
    if (trimmed && trimmed.length > 0) {
      nodes.push({
        text: text,
        trimmed: trimmed,
        index: match.index + 1, // position of text start
        length: text.length
      });
    }
  }
  
  return nodes;
}

const sourceBody = extractBody(source);
const targetBody = extractBody(target);

const sourceNodes = extractTextNodes(sourceBody);
const targetNodes = extractTextNodes(targetBody);

console.log(`Source text nodes: ${sourceNodes.length}`);
console.log(`Target text nodes: ${targetNodes.length}`);

// Build replacement map by matching nodes positionally
// Since both files have same structure, nodes should align
const replacements = new Map();

const minLen = Math.min(sourceNodes.length, targetNodes.length);
let matchCount = 0;
let diffCount = 0;

for (let i = 0; i < minLen; i++) {
  const src = sourceNodes[i];
  const tgt = targetNodes[i];
  
  if (src.trimmed !== tgt.trimmed) {
    diffCount++;
    // Only replace if the target text exists and source text is different
    if (!replacements.has(tgt.trimmed)) {
      replacements.set(tgt.trimmed, src.trimmed);
    }
  } else {
    matchCount++;
  }
}

console.log(`Matching nodes: ${matchCount}`);
console.log(`Different nodes: ${diffCount}`);
console.log(`Unique replacements to make: ${replacements.size}`);

// Show first 30 replacements
let shown = 0;
for (const [from, to] of replacements) {
  if (shown < 30) {
    console.log(`  "${from.substring(0, 60)}" → "${to.substring(0, 60)}"`);
    shown++;
  }
}

// Apply replacements to target HTML
// We need to replace text content between tags carefully
let updatedTarget = target;
let appliedCount = 0;
let skippedCount = 0;

// Sort by length descending to avoid partial replacements
const sortedReplacements = Array.from(replacements.entries())
  .sort((a, b) => b[0].length - a[0].length);

for (const [fromText, toText] of sortedReplacements) {
  if (fromText === toText) continue;
  
  // Escape special regex chars
  const escaped = fromText.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
  
  // Match text between > and < (text node content)
  // Use word boundary approach to avoid replacing inside attributes
  const pattern = new RegExp(`(>)([ \\t]*)${escaped}([ \\t]*)(<)`, 'g');
  
  const newHtml = updatedTarget.replace(pattern, (match, gt, ws1, ws2, lt) => {
    return `${gt}${ws1}${toText}${ws2}${lt}`;
  });
  
  if (newHtml !== updatedTarget) {
    updatedTarget = newHtml;
    appliedCount++;
  } else {
    skippedCount++;
    // Try without whitespace capture
    const pattern2 = new RegExp(`(>)${escaped}(<)`, 'g');
    const newHtml2 = updatedTarget.replace(pattern2, `$1${toText}$2`);
    if (newHtml2 !== updatedTarget) {
      updatedTarget = newHtml2;
      appliedCount++;
      skippedCount--;
    }
  }
}

console.log(`\nApplied: ${appliedCount} replacements`);
console.log(`Skipped: ${skippedCount} replacements`);

// Save
fs.writeFileSync(targetFile, updatedTarget, 'utf8');
console.log(`\nSaved to ${targetFile}`);
