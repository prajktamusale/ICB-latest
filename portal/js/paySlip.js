const userName = document.getElementById("emp_name").textContent.toUpperCase();
const month = document.getElementById("pay_month").textContent;
const year = document.getElementById("pay_year").textContent;
const userDesignation = document.getElementById("emp_designation").textContent;
const UserID = document.getElementById("emp_id").textContent;
const joiningDate = document.getElementById("emp_joining_date").textContent;
const monthArr = ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"];
const d = new Date(joiningDate);
let dateDay = d.getDay();
let dateMonth = monthArr[d.getMonth()];
let dateYear = d.getFullYear();
let finalJoiningDate = `${dateMonth} ${dateDay}, ${dateYear}`;
const userPAN = document.getElementById("emp_pan").textContent.toUpperCase();
const userDaysPresent = document.getElementById("emp_days_present").textContent;
const userLOP = document.getElementById("emp_lop").textContent;
const uan = getValidValue(document.getElementById("emp_uan").textContent.trim());
const pfNo = getValidValue(document.getElementById("emp_pfno").textContent.trim());
const esicNo = getValidValue(document.getElementById("emp_esicno").textContent.trim());
const tan = document.getElementById("comp_tan").textContent;
const userBank = document.getElementById("emp_account").textContent;
const userIFSC = document.getElementById("emp_ifsc").textContent.toUpperCase();
const userBasicSalary = document.getElementById("emp_basic_pay").textContent;
const userTA = document.getElementById("emp_travelAll").textContent;
const userOA = document.getElementById("emp_otherAll").textContent;
const userBonus = getValidValue(document.getElementById("emp_bonus").textContent.trim());
const userPT = getValidValue(document.getElementById("emp_pt").textContent.trim());
const userPF = getValidValue(document.getElementById("emp_pf").textContent.trim());
const userInsurance = getValidValue(document.getElementById("emp_insurance").textContent.trim());
const userTDS = getValidValue(document.getElementById("emp_tds").textContent.trim());
const userOtherDed = getValidValue(document.getElementById("emp_otherDed").textContent.trim());
const userTotalIncome = document.getElementById("emp_totalincome").textContent;
const userMonthIncome = document.getElementById("emp_monthincome").textContent;
const userTotalDeduction = getValidValue(document.getElementById("emp_deduction").textContent.trim());
const netIncome = document.getElementById("emp_net_income").textContent;
const netPayable = document.getElementById("emp_net_payable").textContent;
const netPayableWords = document.getElementById("emp_net_payable_word").innerText;
const slipDate = document.getElementById("slip_date").innerText;
const dd = new Date(slipDate);
let finalSlipDate = dd.toDateString();

function getValidValue(value) {
  return value === null || value === '' || parseFloat(value) === 0 ? 'NA' : value;
}

const { PDFDocument, rgb, degrees } = PDFLib;

// const capitalize = (str, lower = false) =>
//   (lower ? str.toLowerCase() : str).replace(/(?:^|\s|["'([{])+\S/g, (match) =>
//     match.toUpperCase()
//   );

// submitBtn.addEventListener("click", () => {
//   const val = capitalize(userName.value);

//   check if the text is empty or not
//   if (val.trim() !== "" && userName.checkValidity()) {
//     console.log(val);
//     generatePDF(val);
//   } else {
//     userName.reportValidity();
//   }
// });

const generatePDF = async () => {
  const existingPdfBytes = await fetch("../database/paySlip.pdf").then((res) =>
    res.arrayBuffer()
  );

  // Load a PDFDocument from the existing PDF bytes
  const pdfDoc = await PDFDocument.load(existingPdfBytes);
  pdfDoc.registerFontkit(fontkit);

  //get font
  const fontBytes = await fetch("../AdditionalFonts/Inter-Medium.ttf").then((res) =>
    res.arrayBuffer()
  );
  const fontBytes1 = await fetch("../AdditionalFonts/Inter-SemiBold.ttf").then((res) =>
    res.arrayBuffer()
  );
  const fontBytes2 = await fetch("../AdditionalFonts/Inter-Bold.ttf").then((res) =>
    res.arrayBuffer()
  );

  // Embed our custom font in the document
  const InterMedFont = await pdfDoc.embedFont(fontBytes);
  const InterSemiBoldFont = await pdfDoc.embedFont(fontBytes1);
  const InterBoldFont = await pdfDoc.embedFont(fontBytes2);

  // Get the first page of the document
  const pages = pdfDoc.getPages();
  const firstPage = pages[0];

//   Draw a string of text diagonally across the first page
  firstPage.drawText(userName, {
    x: 137,
    y: 648.5,
    size: 12,
    font: InterSemiBoldFont,
    color: rgb(0, 0, 0),
  });
  firstPage.drawText(month, {
    x: 190,
    y: 707,
    size: 15,
    font: InterBoldFont,
    color: rgb(0, 0, 0),
  });
  firstPage.drawText(year, {
    x: 190,
    y: 692,
    size: 15,
    font: InterBoldFont,
    color: rgb(0, 0, 0),
  });
  firstPage.drawText(userDesignation, {
    x: 142,
    y: 598,
    size: 12,
    font: InterMedFont,
    color: rgb(0, 0, 0),
  });
  firstPage.drawText(uan, {
    x: 375,
    y: 598,
    size: 12,
    font: InterMedFont,
    color: rgb(0, 0, 0),
  });
  firstPage.drawText(UserID, {
    x: 142,
    y: 574,
    size: 12,
    font: InterMedFont,
    color: rgb(0, 0, 0),
  });
  firstPage.drawText(pfNo, {
    x: 375,
    y: 574,
    size: 12,
    font: InterMedFont,
    color: rgb(0, 0, 0),
  });
  firstPage.drawText(finalJoiningDate, {
    x: 142,
    y: 549,
    size: 12,
    font: InterMedFont,
    color: rgb(0, 0, 0),
  });
  firstPage.drawText(esicNo, {
    x: 375,
    y: 549,
    size: 12,
    font: InterMedFont,
    color: rgb(0, 0, 0),
  });
  firstPage.drawText(userPAN, {
    x: 142,
    y: 524,
    size: 12,
    font: InterMedFont,
    color: rgb(0, 0, 0),
  });
  firstPage.drawText(tan, {
    x: 375,
    y: 524,
    size: 12,
    font: InterMedFont,
    color: rgb(0, 0, 0),
  });
  firstPage.drawText(userDaysPresent, {
    x: 142,
    y: 500,
    size: 12,
    font: InterMedFont,
    color: rgb(0, 0, 0),
  });
  firstPage.drawText(userBank, {
    x: 375,
    y: 500,
    size: 12,
    font: InterMedFont,
    color: rgb(0, 0, 0),
  });
  firstPage.drawText(userLOP, {
    x: 142,
    y: 475,
    size: 12,
    font: InterMedFont,
    color: rgb(0, 0, 0),
  });
  firstPage.drawText(userIFSC, {
    x: 375,
    y: 475,
    size: 12,
    font: InterMedFont,
    color: rgb(0, 0, 0),
  });
  firstPage.drawText(userBasicSalary, {
    x: 142,
    y: 404,
    size: 12,
    font: InterMedFont,
    color: rgb(0, 0, 0),
  });
  firstPage.drawText(userPT, {
    x: 375,
    y: 404.5,
    size: 12,
    font: InterMedFont,
    color: rgb(0, 0, 0),
  });
  firstPage.drawText(userTA, {
    x: 142,
    y: 380,
    size: 12,
    font: InterMedFont,
    color: rgb(0, 0, 0),
  });
  firstPage.drawText(userPF, {
    x: 375,
    y: 380,
    size: 12,
    font: InterMedFont,
    color: rgb(0, 0, 0),
  });
  firstPage.drawText(userOA, {
    x: 142,
    y: 356,
    size: 12,
    font: InterMedFont,
    color: rgb(0, 0, 0),
  });
  firstPage.drawText(userInsurance, {
    x: 375,
    y: 356,
    size: 12,
    font: InterMedFont,
    color: rgb(0, 0, 0),
  });
  firstPage.drawText(userTotalIncome, {
    x: 142,
    y: 331,
    size: 12,
    font: InterMedFont,
    color: rgb(0, 0, 0),
  });
  firstPage.drawText(userTDS, {
    x: 375,
    y: 331,
    size: 12,
    font: InterMedFont,
    color: rgb(0, 0, 0),
  });
  firstPage.drawText(userBonus, {
    x: 142,
    y: 306,
    size: 12,
    font: InterMedFont,
    color: rgb(0, 0, 0),
  });
  firstPage.drawText(userOtherDed, {
    x: 375,
    y: 306,
    size: 12,
    font: InterMedFont,
    color: rgb(0, 0, 0),
  });firstPage.drawText(userMonthIncome, {
    x: 142,
    y: 277,
    size: 12,
    font: InterMedFont,
    color: rgb(0, 0, 0),
  });
  firstPage.drawText(userTotalDeduction, {
    x: 375,
    y: 277,
    size: 12,
    font: InterMedFont,
    color: rgb(0, 0, 0),
  });
  firstPage.drawText(netIncome, {
    x: 142,
    y: 227,
    size: 12,
    font: InterMedFont,
    color: rgb(0, 0, 0),
  });
  firstPage.drawText(netPayable, {
    x: 142,
    y: 202,
    size: 12,
    font: InterMedFont,
    color: rgb(0, 0, 0),
  });
  firstPage.drawText(netPayableWords, {
    x: 142,
    y: 177,
    size: 11,
    font: InterMedFont,
    color: rgb(0, 0, 0),
  });
  firstPage.drawText(finalSlipDate, {
    x: 68,
    y: 57.5,
    size: 11,
    font: InterMedFont,
    color: rgb(0, 0, 0),
  });

  // Serialize the PDFDocument to bytes (a Uint8Array)
  const pdfBytes = await pdfDoc.save();
  console.log("Done creating");

  // this was for creating uri and showing in iframe

//   const pdfDataUri = await pdfDoc.saveAsBase64({ dataUri: true });
//   window.open(pdfDataUri);
//   document.querySelector("#mypdf").src = pdfDataUri;

  var file = new File(
    [pdfBytes],
    "Pay Slip "+month+" "+year+" | "+userName+".pdf",
    {
      type: "application/pdf;charset=utf-8",
    }
  );
 saveAs(file);
};

// init();
generatePDF();