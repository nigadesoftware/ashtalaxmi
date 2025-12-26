{/* <script> */}
function qtycal()
{
    var salequantity = Number(document.getElementById("quantity").value);
    var salerate = Number(document.getElementById("rate").value);
    /* var transportationrate = Number(document.getElementById("transportationrate").value); */
    var gstrate = Number(document.getElementById("gstrate").value);
    var statecode = Number(document.getElementById("statecode").value);
    var discountamount = Number(document.getElementById("discountamount").value);
    var packingforwardingamount = Number(document.getElementById("packingforwardingamount").value);
    var sgstrate;
    var cgstrate;
    var igstrate;
    var ugstrate;
    if (salequantity == "")
        salequantity = 0;
    if (salerate == "")
        salerate = 0; 
    if (discountamount == "")
        discountamount = 0;  
    if (packingforwardingamount == "")
        packingforwardingamount = 0; 
        
        
    if (gstrate == "")
    {
        sgstrate = 0;
        cgstrate = 0;
        igstrate = 0;
        ugstrate = 0;
    }
    else
    {
        if (statecode == 27)
        {
            sgstrate = gstrate/2;
            cgstrate = gstrate/2;
            igstrate = 0;
            ugstrate = 0;
        }
        else
        {
            igstrate = gstrate;
            sgstrate = 0;
            cgstrate = 0;
            ugstrate = 0;
        }
    }
    var amount=0;
    var discountedamount=0;
    var taxableamount=0;
    var sgstamount=0;
    var cgstamount=0;
    var igstamount=0;
    var ugstamount=0;
    var vatamount=0;
    var totaltaxamount=0;
    var grossamount=0;
    totaltaxrate = sgstrate+cgstrate+igstrate+ugstrate;
    amount = Number((salerate*salequantity).toFixed(2));
    discountedamount = Number((amount - discountamount).toFixed(2));
    taxableamount = Number((discountedamount+packingforwardingamount).toFixed(2));
    /* transportationamount = Number((transportationrate*salequantity).toFixed(2)); */

    sgstamount = Number((taxableamount*sgstrate/100).toFixed(2));
    cgstamount = Number((taxableamount*cgstrate/100).toFixed(2));
    igstamount = Number((taxableamount*igstrate/100).toFixed(2));
    ugstamount = Number((taxableamount*ugstrate/100).toFixed(2));
    /* vatamount = Number((taxableamount*vatrate/100).toFixed(2)); */
    totaltaxamount = sgstamount+cgstamount+igstamount+ugstamount;
    grossamount = taxableamount+totaltaxamount;
    

    if (!isNaN(amount)) 
    { 
        document.getElementById('amount').value = amount;
    }
    if (!isNaN(discountedamount)) 
    { 
        document.getElementById('discountedamount').value = discountedamount;
    }
    if (!isNaN(taxableamount)) 
    { 
        document.getElementById('taxableamount').value = taxableamount;
    }
    if (!isNaN(sgstamount)) 
    { 
        document.getElementById('sgstamount').value = sgstamount;
    } 
    if (!isNaN(cgstamount)) 
    { 
        document.getElementById('cgstamount').value = cgstamount;
    } 
    if (!isNaN(igstamount)) 
    { 
        document.getElementById('igstamount').value = igstamount;
    } 
    if (!isNaN(ugstamount)) 
    { 
        document.getElementById('ugstamount').value = ugstamount;
    } 
    /* if (!isNaN(vatamount)) 
    { 
        document.getElementById('vatamount').value = vatamount;
    } */
    if (!isNaN(totaltaxamount)) 
    { 
        document.getElementById('totalgstamount').value = totaltaxamount;
    } 
    if (!isNaN(grossamount)) 
    { 
        document.getElementById('itemtotal').value = grossamount;
    } 
}
{/*         </script> */}