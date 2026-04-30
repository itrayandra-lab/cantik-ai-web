const fs = require('fs');

const src = fs.readFileSync('index_local_backup.html', 'utf8');
const tgt = fs.readFileSync('index.html', 'utf8');

console.log('Source length:', src.length);
console.log('Target length:', tgt.length);

// Check first > in source
const firstGt = src.indexOf('>');
console.log('First > in source at:', firstGt);
console.log('Char before:', src.charCodeAt(firstGt - 1));
console.log('Char after:', src.charCodeAt(firstGt + 1));

// Check if source has \r\n or \n
const hasCRLF = src.includes('\r\n');
const hasLF = src.includes('\n');
console.log('Source has CRLF:', hasCRLF);
console.log('Source has LF:', hasLF);

const hasCRLF2 = tgt.includes('\r\n');
const hasLF2 = tgt.includes('\n');
console.log('Target has CRLF:', hasCRLF2);
console.log('Target has LF:', hasLF2);

// Try simple regex on source
const testRegex = />([^<]+)</g;
let count = 0;
let m;
while ((m = testRegex.exec(src)) !== null) {
  const text = m[1].trim();
  if (text && text.length > 0 && count < 5) {
    console.log(`Match ${count}: "${text.substring(0, 50)}"`);
  }
  if (text) count++;
}
console.log('Total matches in source:', count);

// Try on target
const testRegex2 = />([^<]+)</g;
let count2 = 0;
while ((m = testRegex2.exec(tgt)) !== null) {
  const text = m[1].trim();
  if (text && text.length > 0 && count2 < 5) {
    console.log(`Target match ${count2}: "${text.substring(0, 50)}"`);
  }
  if (text) count2++;
}
console.log('Total matches in target:', count2);
