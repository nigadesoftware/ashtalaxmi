create or replace procedure Sale_Bill_Wrong_Posting  is
begin
declare
m_saletransactionnumber number;
cursor c0 is
select saletransactionnumber 
from (
select t.saletransactionnumber,(h.grossamount+h.totaltaxamount) as invoiceamount,v.totaldebit,v.totalcredit 
from saleaccountbridge t,saleinvoiceheader h,nst_nasaka_finance.voucherheader v
where t.saletransactionnumber=h.transactionnumber and t.accounttransactionnumber=v.transactionnumber)t
where t.invoiceamount<>t.totaldebit or t.invoiceamount<>t.totalcredit;
begin
      open c0;
      loop
        fetch c0 into m_saletransactionnumber;
        if c0%notfound then
           exit;
        end if;
        sale_bill_swapp_posting(p_saletransactionnumber => m_saletransactionnumber,p_action => 2 );
        commit;
      end loop;
      close c0;
end;
end Sale_Bill_Wrong_Posting;
/
