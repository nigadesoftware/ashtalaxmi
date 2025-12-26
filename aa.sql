create or replace function ac_ledger_balance(p_accode in number,p_dt in date,p_flag in number) return number  is
  Result  varchar2(9);
begin
declare
   finStartDate date;
    FinYear      varchar2(9);
    mnor_sub_bank number;
    opening number;
    cr_balance number;
    dr_balance number;
    crdramt number;
   cursor c0 is
   select a.nnor_sub_bank from ac_ml a where a.nac_code=p_accode ;
   cursor c1 is
   select nvl(namount,0)
        from ac_op_bal a,ac_account c
      where a.nac_code=c.nac_code 
      --and c.nnor_sub_bank<>3 
      and c.NAC_CODE=p_accode and a.vfin_year = finyear;
   cursor c2 is
   select nvl(sum(cr_balance),0) as cr_balance, nvl(sum(dr_balance),0) as dr_balance
   from (
   select vind_code,case when balance>0 then balance else 0 end as cr_balance,case when balance<0 then balance else 0 end as dr_balance
   from (
   select vind_code,sum(balance) as balance
   from(
   select a.vind_code,nvl(sum(a.namount),0) as balance
        from ac_ind_op_bal a
       where a.vfin_year = finyear and a.nac_code=p_accode
       group by vind_code 
       union all
       select d.vind_code,nvl(sum(d.ncr_amt), 0) - nvl(sum(d.ndr_amt), 0)
        from ac_voucher_header h, ac_voucher_detail d
       where h.ntrans_no = d.ntrans_no
         and h.dvou_date >= finStartDate
         and h.dvou_date <= p_dt
         and h.nvou_status = 9
         and h.vfin_year=finyear
         and d.nac_code=p_accode
       group by d.vind_code)
       group by vind_code));
   cursor c3 is
   select nvl(sum(d.ncr_amt), 0) - nvl(sum(d.ndr_amt), 0)  as balance
        from ac_voucher_header h, ac_voucher_detail d
       where h.ntrans_no = d.ntrans_no
         and h.dvou_date >= finStartDate
         and h.dvou_date <= p_dt
         and h.nvou_status = 9
         and h.vfin_year=finyear
         and d.nac_code=p_accode
       group by nac_code;
   cursor c4 is
   select nvl(sum(d.ndr_amt), 0) - nvl(sum(d.ncr_amt), 0)  as balance
        from ac_voucher_header h, ac_voucher_detail d
       where h.ntrans_no = d.ntrans_no
         and h.dvou_date >= finStartDate
         and h.dvou_date <= p_dt
         and h.nvou_status = 9
         and h.vfin_year=finyear
         and h.nch_cq=1;
   begin      
   finyear      := ac_fin_year(p_dt);
    finStartDate := to_date('01-Apr-' || substr(finyear,1,4));
    open c0;
    fetch c0 into mnor_sub_bank;
    close c0;
    if (mnor_sub_bank<>3 and p_flag in (0,3)) or (p_flag=4) then
       if p_flag=3 then
          open c1;
         fetch c1 into opening;
         close c1;
         open c4;
         fetch c4 into crdramt;
         close c4; 
         return nvl(opening,0)+nvl(crdramt,0);
       else
         open c1;
         fetch c1 into opening;
         close c1;
         open c3;
         fetch c3 into crdramt;
         close c3;
         return nvl(opening,0)+nvl(crdramt,0);
      end if;
    else
       open c2;
       fetch c2 into cr_balance,dr_balance;
       close c2;
       --cr-dr
       if p_flag=0 then
          return nvl(cr_balance,0)+nvl(dr_balance,0);
       --cr
       else if p_flag=1 then
          return nvl(cr_balance,0);
       --dr
       else if p_flag=2 then
          return nvl(dr_balance,0);      
       end if;
       end if;
       end if;
    end if;
    
   end;
end ac_ledger_balance;
