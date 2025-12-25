// archiveCase.js
const { ethers } = require("ethers");
require('dotenv').config();

// =====================
// 1️⃣ إعداد RPC و Wallet
// =====================
const provider = new ethers.JsonRpcProvider(process.env.RPC_URL || "https://bsc-testnet.publicnode.com");
const wallet = new ethers.Wallet(process.env.PRIVATE_KEY, provider);

// =====================
// 2️⃣ إعداد العقد
// =====================
const CONTRACT_ADDRESS = process.env.CONTRACT_ADDRESS;
const ABI = require('./app/abi.json');
const contract = new ethers.Contract(CONTRACT_ADDRESS, ABI, wallet);

// =====================
// 3️⃣ أخذ القيم من CLI
// =====================
const args = process.argv.slice(2);
if (args.length < 3) {
    console.log("ERROR_TX=Usage: node archiveCase.js <caseNumber> <documentNumber> <documentType>");
    process.exit(1);
}

const [caseNumber, documentNumber, documentType] = args;

// =====================
// 4️⃣ دالة إرسال المعاملة
// =====================
async function archiveCase(caseNumber, documentNumber, documentType) {
    try {
        const tx = await contract.archiveCase(caseNumber, documentNumber, documentType);

        // طباعة TX hash بطريقة PHP يقدر يلتقطها
        console.log(`TX_HASH=${tx.hash}`);

        const receipt = await tx.wait();

        // طباعة Block Number بنفس الطريقة
        console.log(`BLOCK=${receipt.blockNumber}`);

    } catch (error) {
        // بدل console.error استخدم console.log لتلتقطه PHP
        console.log(`ERROR_TX=${error.message || error}`);
        process.exit(1);
    }
}

// =====================
// 5️⃣ تشغيل الدالة
// =====================
archiveCase(caseNumber, documentNumber, documentType);
