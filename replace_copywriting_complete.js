/**
 * Script COMPLETE: Mengganti copywriting dari index_local_backup.html ke index.html
 * Source: UTF-16 LE, Target: UTF-8 CRLF
 */

const fs = require('fs');

const sourceFile = 'index_local_backup.html';
const targetFile = 'index.html';

// Read source as UTF-16 LE
const sourceBuffer = fs.readFileSync(sourceFile);
let sourceContent;
if (sourceBuffer[0] === 0xFF && sourceBuffer[1] === 0xFE) {
  sourceContent = sourceBuffer.toString('utf16le').substring(1); // Skip BOM char
} else {
  sourceContent = sourceBuffer.toString('utf16le');
}

const targetContent = fs.readFileSync(targetFile, 'utf8');

// Backup
fs.writeFileSync(targetFile + '.backup_final', targetContent, 'utf8');
console.log('Backup created: index.html.backup_final');

/**
 * Remove script/style/comment blocks for text extraction
 */
function removeNonText(html) {
  return html
    .replace(/<script[\s\S]*?<\/script>/gi, match => ' '.repeat(match.length))
    .replace(/<style[\s\S]*?<\/style>/gi, match => ' '.repeat(match.length))
    .replace(/<!--[\s\S]*?-->/g, match => ' '.repeat(match.length));
}

/**
 * Extract text nodes - returns array of {trimmed, original}
 */
function extractTextNodes(html) {
  const cleaned = removeNonText(html);
  const nodes = [];
  
  const regex = />([^<]+)</g;
  let m;
  while ((m = regex.exec(cleaned)) !== null) {
    const text = m[1];
    const trimmed = text.trim();
    if (trimmed && trimmed.length > 0) {
      nodes.push({
        original: text,
        trimmed: trimmed
      });
    }
  }
  return nodes;
}

const sourceNodes = extractTextNodes(sourceContent);
const targetNodes = extractTextNodes(targetContent);

console.log(`Source text nodes: ${sourceNodes.length}`);
console.log(`Target text nodes: ${targetNodes.length}`);

// Build replacement map by positional matching
// Both files have same HTML structure, so nodes should align positionally
const replacements = new Map(); // target_trimmed -> source_trimmed

const minLen = Math.min(sourceNodes.length, targetNodes.length);
let diffCount = 0;
let sameCount = 0;

for (let i = 0; i < minLen; i++) {
  const src = sourceNodes[i];
  const tgt = targetNodes[i];
  
  if (src.trimmed !== tgt.trimmed) {
    diffCount++;
    if (!replacements.has(tgt.trimmed)) {
      replacements.set(tgt.trimmed, src.trimmed);
    }
  } else {
    sameCount++;
  }
}

// Handle extra nodes in source (if source has more nodes)
if (sourceNodes.length > targetNodes.length) {
  console.log(`Source has ${sourceNodes.length - targetNodes.length} extra nodes`);
}

console.log(`Same: ${sameCount}, Different: ${diffCount}`);
console.log(`Unique replacements: ${replacements.size}`);

// Show all replacements
console.log('\n=== REPLACEMENT MAP ===');
let idx = 0;
for (const [from, to] of replacements) {
  idx++;
  console.log(`${idx}. "${from.substring(0, 70)}" → "${to.substring(0, 70)}"`);
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
  const pattern = new RegExp(`(>)([ \\t\\r\\n]*)${escaped}([ \\t\\r\\n]*)(<)`, 'g');
  
  const newContent = updatedContent.replace(pattern, (match, gt, ws1, ws2, lt) => {
    return `${gt}${ws1}${toText}${ws2}${lt}`;
  });
  
  if (newContent !== updatedContent) {
    updatedContent = newContent;
    appliedCount++;
    console.log(`✓ "${fromText.substring(0, 55)}" → "${toText.substring(0, 55)}"`);
  } else {
    failedCount++;
    console.log(`✗ NOT FOUND: "${fromText.substring(0, 55)}"`);
  }
}

// Also handle title tag specifically
const titleSrc = sourceContent.match(/<title>\s*([\s\S]*?)\s*<\/title>/);
const titleTgt = targetContent.match(/<title>\s*([\s\S]*?)\s*<\/title>/);
if (titleSrc && titleTgt && titleSrc[1].trim() !== titleTgt[1].trim()) {
  const newTitle = updatedContent.replace(
    /<title>[\s\S]*?<\/title>/,
    `<title>\n      ${titleSrc[1].trim()}\n    </title>`
  );
  if (newTitle !== updatedContent) {
    updatedContent = newTitle;
    appliedCount++;
    console.log(`✓ Title: "${titleTgt[1].trim()}" → "${titleSrc[1].trim()}"`);
  }
}

// Handle meta description
const metaDescSrc = sourceContent.match(/name="description"\s+content="([^"]+)"/);
const metaDescTgt = targetContent.match(/name="description"\s+content="([^"]+)"/);
if (metaDescSrc && metaDescTgt && metaDescSrc[1] !== metaDescTgt[1]) {
  updatedContent = updatedContent.replace(
    /name="description"\s+content="[^"]+"/,
    `name="description" content="${metaDescSrc[1]}"`
  );
  console.log(`✓ Meta description updated`);
  appliedCount++;
}

// Handle meta og:title
const ogTitleSrc = sourceContent.match(/property="og:title"\s+content="([^"]+)"/);
const ogTitleTgt = targetContent.match(/property="og:title"\s+content="([^"]+)"/);
if (ogTitleSrc && ogTitleTgt && ogTitleSrc[1] !== ogTitleTgt[1]) {
  updatedContent = updatedContent.replace(
    /property="og:title"\s+content="[^"]+"/,
    `property="og:title" content="${ogTitleSrc[1]}"`
  );
  console.log(`✓ OG title updated`);
  appliedCount++;
}

// Handle meta og:description
const ogDescSrc = sourceContent.match(/property="og:description"\s+content="([^"]+)"/);
const ogDescTgt = targetContent.match(/property="og:description"\s+content="([^"]+)"/);
if (ogDescSrc && ogDescTgt && ogDescSrc[1] !== ogDescTgt[1]) {
  updatedContent = updatedContent.replace(
    /property="og:description"\s+content="[^"]+"/,
    `property="og:description" content="${ogDescSrc[1]}"`
  );
  console.log(`✓ OG description updated`);
  appliedCount++;
}

// Handle meta twitter:title
const twTitleSrc = sourceContent.match(/property="twitter:title"\s+content="([^"]+)"/);
const twTitleTgt = targetContent.match(/property="twitter:title"\s+content="([^"]+)"/);
if (twTitleSrc && twTitleTgt && twTitleSrc[1] !== twTitleTgt[1]) {
  updatedContent = updatedContent.replace(
    /property="twitter:title"\s+content="[^"]+"/,
    `property="twitter:title" content="${twTitleSrc[1]}"`
  );
  console.log(`✓ Twitter title updated`);
  appliedCount++;
}

// Handle meta twitter:description
const twDescSrc = sourceContent.match(/property="twitter:description"\s+content="([^"]+)"/);
const twDescTgt = targetContent.match(/property="twitter:description"\s+content="([^"]+)"/);
if (twDescSrc && twDescTgt && twDescSrc[1] !== twDescTgt[1]) {
  updatedContent = updatedContent.replace(
    /property="twitter:description"\s+content="[^"]+"/,
    `property="twitter:description" content="${twDescSrc[1]}"`
  );
  console.log(`✓ Twitter description updated`);
  appliedCount++;
}

console.log(`\n=== SUMMARY ===`);
console.log(`Applied: ${appliedCount}`);
console.log(`Failed: ${failedCount}`);

// Save as UTF-8
fs.writeFileSync(targetFile, updatedContent, 'utf8');
console.log(`\nSaved to ${targetFile} (UTF-8)`);
