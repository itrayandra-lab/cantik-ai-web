/**
 * Script FINAL v2: Handle UTF-16 LE source file
 * Source file (index_local_backup.html) is UTF-16 LE encoded
 * Target file (index.html) is UTF-8 with CRLF
 */

const fs = require('fs');

const sourceFile = 'index_local_backup.html';
const targetFile = 'index.html';

// Read source as UTF-16 LE (it has BOM: FF FE or similar)
const sourceBuffer = fs.readFileSync(sourceFile);
const targetContent = fs.readFileSync(targetFile, 'utf8');

// Detect encoding
console.log('Source BOM bytes:', sourceBuffer[0].toString(16), sourceBuffer[1].toString(16));

// UTF-16 LE BOM is FF FE, but we see 65533 65533 which is replacement chars
// The actual bytes might be different. Let's check raw bytes
console.log('First 10 raw bytes:', Array.from(sourceBuffer.slice(0, 10)).map(b => b.toString(16).padStart(2, '0')).join(' '));

// Try reading as UTF-16 LE
let sourceContent;
try {
  // Check if it's UTF-16 LE (BOM: FF FE)
  if (sourceBuffer[0] === 0xFF && sourceBuffer[1] === 0xFE) {
    sourceContent = sourceBuffer.toString('utf16le').substring(1); // Skip BOM
    console.log('Detected UTF-16 LE with BOM');
  } else if (sourceBuffer[0] === 0xFE && sourceBuffer[1] === 0xFF) {
    // UTF-16 BE
    sourceContent = sourceBuffer.swap16().toString('utf16le').substring(1);
    console.log('Detected UTF-16 BE with BOM');
  } else {
    // Try UTF-16 LE without BOM (null bytes between chars)
    sourceContent = sourceBuffer.toString('utf16le');
    console.log('Trying UTF-16 LE without BOM');
  }
} catch(e) {
  console.error('Error reading source:', e);
  process.exit(1);
}

console.log('Source content length:', sourceContent.length);
console.log('First 100 chars:', sourceContent.substring(0, 100));

// Backup target
fs.writeFileSync(targetFile + '.backup_v2', targetContent, 'utf8');
console.log('Backup created');

// Now extract text nodes from both
function removeScriptStyle(html) {
  return html
    .replace(/<script[\s\S]*?<\/script>/gi, match => ' '.repeat(match.length))
    .replace(/<style[\s\S]*?<\/style>/gi, match => ' '.repeat(match.length))
    .replace(/<!--[\s\S]*?-->/g, match => ' '.repeat(match.length));
}

function extractTextNodes(html) {
  const cleaned = removeScriptStyle(html);
  const nodes = [];
  
  const regex = />([^<]+)</g;
  let m;
  while ((m = regex.exec(cleaned)) !== null) {
    const text = m[1];
    const trimmed = text.trim();
    if (trimmed && trimmed.length > 0 && !looksLikeCode(trimmed)) {
      nodes.push({
        text: text,
        trimmed: trimmed,
        index: m.index + 1
      });
    }
  }
  return nodes;
}

function looksLikeCode(text) {
  if (/^[a-z-]+\s*:\s*.+;$/.test(text)) return true;
  if (/^\{/.test(text) || /^\}/.test(text)) return true;
  if (/^(var|let|const|function|if|for|return)\s/.test(text)) return true;
  if (/^\/\//.test(text) || /^\/\*/.test(text)) return true;
  return false;
}

const sourceNodes = extractTextNodes(sourceContent);
const targetNodes = extractTextNodes(targetContent);

console.log(`\nSource text nodes: ${sourceNodes.length}`);
console.log(`Target text nodes: ${targetNodes.length}`);

// Show first 10 source nodes
console.log('\nFirst 10 source nodes:');
sourceNodes.slice(0, 10).forEach((n, i) => console.log(`  ${i}: "${n.trimmed.substring(0, 60)}"`));

console.log('\nFirst 10 target nodes:');
targetNodes.slice(0, 10).forEach((n, i) => console.log(`  ${i}: "${n.trimmed.substring(0, 60)}"`));
