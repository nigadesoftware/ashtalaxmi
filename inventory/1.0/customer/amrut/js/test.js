            function qtycal()
            {
                var salequantity = Number(document.getElementById("quantity").value);
                var salerate = Number(document.getElementById("rate").value);
                var gstrate = Number(document.getElementById("gstrate").value);
                var sgstrate;
                var cgstrate;
                var totaltaxamount;
                if (salequantity == "")
                    salequantity = 0;
                if (salerate == "")
                    salerate = 0; 
                if (gstrate == "")
                    gstrate = 0;     
                if (gstrate>0)
                {
                    sgstrate = gstrate /2;
                    cgstrate = gstrate /2;
                }           
                var amount=0;
                var taxableamount=0;
                var sgstamount=0;
                var cgstamount=0;
                var igstamount=0;
                var ugstamount=0;
                var vatamount=0;
                var totaltaxamount=0;
                var grossamount=0;
                totaltaxrate = sgstrate+cgstrate;
                taxableamount = Number((salerate*salequantity).toFixed(2));
                sgstamount = Number((taxableamount*sgstrate/100).toFixed(2));
                cgstamount = Number((taxableamount*cgstrate/100).toFixed(2));
                totaltaxamount = sgstamount+cgstamount;
                grossamount = taxableamount+totaltaxamount;
                if (!isNaN(amount)) 
                { 
                    document.getElementById('amount').value = taxableamount;
                }
                if (!isNaN(sgstamount)) 
                { 
                    document.getElementById('sgstamount').value = sgstamount;
                } 
                if (!isNaN(cgstamount)) 
                { 
                    document.getElementById('cgstamount').value = cgstamount;
                } 
                if (!isNaN(grossamount)) 
                { 
                    document.getElementById('itemamount').value = grossamount;
                } 
            }
