----------------------------------------------
-- Export file for user nst_nasaka_SALE    --
-- Created by admin on 29/03/2019, 20:47:33 --
----------------------------------------------

spool salobj_290319.log

prompt
prompt Creating table GOODSCATEGORY
prompt ============================
prompt
create table nst_nasaka_SALE.GOODSCATEGORY
(
  GOODSCATEGORYCODE    NUMBER not null,
  GOODSCATEGORYNAMEUNI VARCHAR2(100),
  GOODSCATEGORYNAMEENG VARCHAR2(100)
)
;
alter table nst_nasaka_SALE.GOODSCATEGORY
  add constraint PKSCC primary key (GOODSCATEGORYCODE);

prompt
prompt Creating table GOODSSUBCATEGORY
prompt ===============================
prompt
create table nst_nasaka_SALE.GOODSSUBCATEGORY
(
  SUBCATEGORYCODE    NUMBER not null,
  SUBCATEGORYNAMEUNI VARCHAR2(100),
  SUBCATEGORYNAMEENG VARCHAR2(100),
  CATEGORYCODE       NUMBER
)
;
alter table nst_nasaka_SALE.GOODSSUBCATEGORY
  add constraint PKSCSC primary key (SUBCATEGORYCODE);
alter table nst_nasaka_SALE.GOODSSUBCATEGORY
  add constraint FKCATCODE foreign key (CATEGORYCODE)
  references nst_nasaka_SALE.GOODSCATEGORY (GOODSCATEGORYCODE);

prompt
prompt Creating table FINISHEDGOODS
prompt ============================
prompt
create table nst_nasaka_SALE.FINISHEDGOODS
(
  FINISHEDGOODSCODE    NUMBER not null,
  FINISHEDGOODSNAMEUNI VARCHAR2(100),
  FINISHEDGOODSNAMEENG VARCHAR2(100),
  GOODSCATEGORYCODE    NUMBER,
  SUBCATEGORYCODE      NUMBER,
  CONVERSIONFACTOR     NUMBER,
  UNIT                 VARCHAR2(50),
  HSNCODE              NUMBER
)
;
alter table nst_nasaka_SALE.FINISHEDGOODS
  add constraint PKGOODSSALE primary key (FINISHEDGOODSCODE);
alter table nst_nasaka_SALE.FINISHEDGOODS
  add constraint FKGSUBCAT foreign key (SUBCATEGORYCODE)
  references nst_nasaka_SALE.GOODSSUBCATEGORY (SUBCATEGORYCODE);

prompt
prompt Creating table GODOWN
prompt =====================
prompt
create table nst_nasaka_SALE.GODOWN
(
  GODOWNNUMBER       NUMBER not null,
  GODOWNNAMEENG      VARCHAR2(50),
  GODOWNNAMEUNI      VARCHAR2(50),
  GODOWNCATEGORYCODE NUMBER,
  GODOWNCAPACITY     NUMBER
)
;
alter table nst_nasaka_SALE.GODOWN
  add constraint PKGODCODE primary key (GODOWNNUMBER);

prompt
prompt Creating table PURCHASERCATEGORY
prompt ================================
prompt
create table nst_nasaka_SALE.PURCHASERCATEGORY
(
  PURCHASERCATEGORYCODE    NUMBER not null,
  PURCHASERCATEGORYNAMEUNI VARCHAR2(200),
  PURCHASERCATEGORYNAMEENG VARCHAR2(200)
)
;
alter table nst_nasaka_SALE.PURCHASERCATEGORY
  add constraint PCATCD primary key (PURCHASERCATEGORYCODE);

prompt
prompt Creating table GOODSPURCHASER
prompt =============================
prompt
create table nst_nasaka_SALE.GOODSPURCHASER
(
  PURCHASERCODE         NUMBER not null,
  PURCHASERNAMEUNI      VARCHAR2(200),
  PURCHASERNAMEENG      VARCHAR2(200),
  GOODSCATEGORYCODE     NUMBER not null,
  PANNUMBER             VARCHAR2(15),
  GSTINNUMBER           VARCHAR2(18),
  CONTACTNUMBER         VARCHAR2(50),
  CONTACTPERSON         VARCHAR2(200),
  ADDRESS               VARCHAR2(500),
  EMAILID               VARCHAR2(50),
  STATECODE             NUMBER,
  PURCHASERCATEGORYCODE NUMBER
)
;
alter table nst_nasaka_SALE.GOODSPURCHASER
  add constraint GOODSPURCHASERPK primary key (PURCHASERCODE, GOODSCATEGORYCODE);
alter table nst_nasaka_SALE.GOODSPURCHASER
  add constraint FKGOODSPURCHASECD foreign key (PURCHASERCATEGORYCODE)
  references nst_nasaka_SALE.PURCHASERCATEGORY (PURCHASERCATEGORYCODE);
grant select on nst_nasaka_SALE.GOODSPURCHASER to nst_nasaka_DB;

prompt
prompt Creating table GOODSSALEPERMISSION
prompt ==================================
prompt
create table nst_nasaka_SALE.GOODSSALEPERMISSION
(
  TRANSACTIONNUMBER         NUMBER not null,
  PERMISSIONNUMBER          VARCHAR2(200),
  PERMISSIONDATE            DATE,
  PERMISSIONQUNTITY         NUMBER,
  LIFTINGFROMDATE           DATE,
  LIFTINGTODATE             DATE,
  EXTENDEDTRANSACTIONNUMBER NUMBER,
  REMARK                    VARCHAR2(100),
  GOODSCATEGORYCODE         NUMBER
)
;
alter table nst_nasaka_SALE.GOODSSALEPERMISSION
  add constraint PKTRNNO1 primary key (TRANSACTIONNUMBER);
grant select, insert on nst_nasaka_SALE.GOODSSALEPERMISSION to nst_nasaka_DB;

prompt
prompt Creating table GOODSSALETAXES
prompt =============================
prompt
create table nst_nasaka_SALE.GOODSSALETAXES
(
  TAXCODE NUMBER not null,
  TAXNAME VARCHAR2(100)
)
;
alter table nst_nasaka_SALE.GOODSSALETAXES
  add constraint PKTAXM primary key (TAXCODE);

prompt
prompt Creating table SALECATEGORY
prompt ===========================
prompt
create table nst_nasaka_SALE.SALECATEGORY
(
  SALECATEGORYCODE            NUMBER not null,
  SALECATEGORYNAMEUNI         VARCHAR2(50),
  SALECATEGORYNAMEENG         VARCHAR2(50),
  SALECATEGORYSHORTNAME       NVARCHAR2(1),
  GOODSCATEGORYCODE           NUMBER,
  TENDERSALENUMBERSERIESID    NUMBER,
  QUOTATIONSALENUMBERSERIESID NUMBER,
  SALEORDERSALENUMBERSERIESID NUMBER,
  SALEINVOICENUMBERSERIESID   NUMBER,
  SALEMEMONUMBERSERIESID      NUMBER
)
;
alter table nst_nasaka_SALE.SALECATEGORY
  add constraint PKSALECATECDS primary key (SALECATEGORYCODE);

prompt
prompt Creating table GOODSTAXRATE
prompt ===========================
prompt
create table nst_nasaka_SALE.GOODSTAXRATE
(
  TRANSACTIONNUMBER NUMBER not null,
  FINISHEDGOODSCODE NUMBER,
  TAXCODE           NUMBER,
  FROMDATE          DATE,
  TODATE            DATE,
  TAXPERCENT        NUMBER,
  SALECATEGORYCODE  NUMBER
)
;
alter table nst_nasaka_SALE.GOODSTAXRATE
  add constraint PKTAXRATETRANSACTIO primary key (TRANSACTIONNUMBER);
alter table nst_nasaka_SALE.GOODSTAXRATE
  add constraint FK112 foreign key (TAXCODE)
  references nst_nasaka_SALE.GOODSSALETAXES (TAXCODE);
alter table nst_nasaka_SALE.GOODSTAXRATE
  add constraint FKSALCATCD foreign key (SALECATEGORYCODE)
  references nst_nasaka_SALE.SALECATEGORY (SALECATEGORYCODE);

prompt
prompt Creating table PERIODRESETCATEGORY
prompt ==================================
prompt
create table nst_nasaka_SALE.PERIODRESETCATEGORY
(
  PERIODRESETCATEGORYCODE NUMBER not null,
  PERIODRESETCATEGORYNAME VARCHAR2(100)
)
;
alter table nst_nasaka_SALE.PERIODRESETCATEGORY
  add constraint PKPERIODRESETCATEGORID primary key (PERIODRESETCATEGORYCODE);

prompt
prompt Creating table SALENUMBERSERIES
prompt ===============================
prompt
create table nst_nasaka_SALE.SALENUMBERSERIES
(
  SALENUMBERSERIESID      NUMBER(19) not null,
  SALENUMBERSERIESNAMEENG VARCHAR2(1000) not null,
  SALENUMBERSERIESNAMEUNI VARCHAR2(1000) not null,
  SALENUMBERSTARTINGFROM  NUMBER(19) not null,
  PERIODRESETCATEGORYCODE NUMBER(19) not null,
  SALENUMBERPREFIX        VARCHAR2(10) not null,
  GOODSCATEGORYCODE       NUMBER,
  TRANSACTIONCATEGORYCODE NUMBER
)
;
alter table nst_nasaka_SALE.SALENUMBERSERIES
  add constraint PKSALENUMBERSERIESID primary key (SALENUMBERSERIESID);
alter table nst_nasaka_SALE.SALENUMBERSERIES
  add constraint FKGODCATCD foreign key (GOODSCATEGORYCODE)
  references nst_nasaka_SALE.GOODSCATEGORY (GOODSCATEGORYCODE);
alter table nst_nasaka_SALE.SALENUMBERSERIES
  add constraint FKPERRESCAT foreign key (PERIODRESETCATEGORYCODE)
  references nst_nasaka_SALE.PERIODRESETCATEGORY (PERIODRESETCATEGORYCODE);

prompt
prompt Creating table SALEMEMOHEADER
prompt =============================
prompt
create table nst_nasaka_SALE.SALEMEMOHEADER
(
  TRANSACTIONNUMBER       NUMBER not null,
  GOODSCATEGORYCODE       NUMBER not null,
  SALECATEGORYCODE        NUMBER not null,
  YEARPERIODCODE          NUMBER not null,
  SALEMEMONUMBERSERIESID  NUMBER not null,
  SALEMEMONUMBERBASEVALUE VARCHAR2(15) not null,
  MEMONUMBER              NUMBER not null,
  MEMONUMBERPRESUF        VARCHAR2(25) not null,
  MEMODATE                DATE not null,
  BROKERCODE              NUMBER not null,
  PURCHASERCODE           NUMBER not null,
  SHIPPINGPARTYCODE       NUMBER,
  PREPARATIONTIME         DATE,
  REMOVALTIME             DATE,
  VEHICLENUMBER           VARCHAR2(100),
  DRIVERNAME              VARCHAR2(200),
  DRIVERLICENSENO         VARCHAR2(50),
  TAXABLEAMOUNT           NUMBER default 0,
  CGSTAMOUNT              NUMBER default 0,
  SGSTAMOUNT              NUMBER default 0,
  IGSTAMOUNT              NUMBER default 0,
  UGSTAMOUNT              NUMBER default 0,
  VATAMOUNT               NUMBER default 0,
  TOTALTAXAMOUNT          NUMBER default 0,
  GROSSAMOUNT             NUMBER default 0
)
;
alter table nst_nasaka_SALE.SALEMEMOHEADER
  add constraint PKSALE primary key (TRANSACTIONNUMBER);
alter table nst_nasaka_SALE.SALEMEMOHEADER
  add constraint FKBRGODCAT foreign key (BROKERCODE, GOODSCATEGORYCODE)
  references nst_nasaka_SALE.GOODSPURCHASER (PURCHASERCODE, GOODSCATEGORYCODE);
alter table nst_nasaka_SALE.SALEMEMOHEADER
  add constraint FKMENS foreign key (SALEMEMONUMBERSERIESID)
  references nst_nasaka_SALE.SALENUMBERSERIES (SALENUMBERSERIESID);
alter table nst_nasaka_SALE.SALEMEMOHEADER
  add constraint FKPURGODCAT foreign key (PURCHASERCODE, GOODSCATEGORYCODE)
  references nst_nasaka_SALE.GOODSPURCHASER (PURCHASERCODE, GOODSCATEGORYCODE);
alter table nst_nasaka_SALE.SALEMEMOHEADER
  add constraint FKSHIPGODCAT foreign key (SHIPPINGPARTYCODE, GOODSCATEGORYCODE)
  references nst_nasaka_SALE.GOODSPURCHASER (PURCHASERCODE, GOODSCATEGORYCODE);
grant select on nst_nasaka_SALE.SALEMEMOHEADER to nst_nasaka_DB;

prompt
prompt Creating table SALEINVOICEHEADER
prompt ================================
prompt
create table nst_nasaka_SALE.SALEINVOICEHEADER
(
  TRANSACTIONNUMBER          NUMBER not null,
  SALEMEMOTRANSACTIONNUMBER  NUMBER not null,
  GOODSCATEGORYCODE          NUMBER not null,
  SALECATEGORYCODE           NUMBER not null,
  YEARPERIODCODE             NUMBER not null,
  SALEINVOICENUMBERSERIESID  NUMBER not null,
  SALEINVOICENUMBERBASEVALUE VARCHAR2(15),
  INVOICENUMBER              NUMBER,
  INVOICENUMBERPRESUF        VARCHAR2(25),
  INVOICEDATE                DATE not null,
  BROKERCODE                 NUMBER not null,
  PURCHASERCODE              NUMBER,
  SHIPPINGPARTYCODE          NUMBER,
  PREPARATIONTIME            DATE,
  REMOVALTIME                DATE,
  VEHICLENUMBER              VARCHAR2(100),
  DRIVERNAME                 VARCHAR2(200),
  DRIVERLICENSENO            VARCHAR2(50),
  TAXABLEAMOUNT              NUMBER default 0,
  CGSTAMOUNT                 NUMBER default 0,
  SGSTAMOUNT                 NUMBER default 0,
  IGSTAMOUNT                 NUMBER default 0,
  UGSTAMOUNT                 NUMBER default 0,
  VATAMOUNT                  NUMBER default 0,
  TOTALTAXAMOUNT             NUMBER default 0,
  GROSSAMOUNT                NUMBER default 0,
  BOOKINGSTATION             VARCHAR2(100),
  RECEIVINGSTATION           VARCHAR2(100)
)
;
alter table nst_nasaka_SALE.SALEINVOICEHEADER
  add constraint PKINVOCE primary key (TRANSACTIONNUMBER);
alter table nst_nasaka_SALE.SALEINVOICEHEADER
  add constraint FKBRGC foreign key (BROKERCODE, GOODSCATEGORYCODE)
  references nst_nasaka_SALE.GOODSPURCHASER (PURCHASERCODE, GOODSCATEGORYCODE);
alter table nst_nasaka_SALE.SALEINVOICEHEADER
  add constraint FKGOODSCAT foreign key (GOODSCATEGORYCODE)
  references nst_nasaka_SALE.GOODSCATEGORY (GOODSCATEGORYCODE);
alter table nst_nasaka_SALE.SALEINVOICEHEADER
  add constraint FKNUMSERID foreign key (SALEINVOICENUMBERSERIESID)
  references nst_nasaka_SALE.SALENUMBERSERIES (SALENUMBERSERIESID);
alter table nst_nasaka_SALE.SALEINVOICEHEADER
  add constraint FKSALECAT foreign key (SALECATEGORYCODE)
  references nst_nasaka_SALE.SALECATEGORY (SALECATEGORYCODE);
alter table nst_nasaka_SALE.SALEINVOICEHEADER
  add constraint FKSALEMEMOTRNNUM foreign key (SALEMEMOTRANSACTIONNUMBER)
  references nst_nasaka_SALE.SALEMEMOHEADER (TRANSACTIONNUMBER);
create unique index nst_nasaka_SALE.UNIQ_COL1_COL2 on nst_nasaka_SALE.SALEINVOICEHEADER (CASE  WHEN (SALEINVOICENUMBERBASEVALUE IS NOT NULL AND INVOICENUMBER IS NOT NULL) THEN SALEINVOICENUMBERBASEVALUE END, CASE  WHEN (SALEINVOICENUMBERBASEVALUE IS NOT NULL AND INVOICENUMBER IS NOT NULL) THEN INVOICENUMBER END);

prompt
prompt Creating table SALEACCOUNTBRIDGE
prompt ================================
prompt
create table nst_nasaka_SALE.SALEACCOUNTBRIDGE
(
  GOODSCATEGORYCODE        NUMBER not null,
  SALETRANSACTIONNUMBER    NUMBER not null,
  ACCOUNTTRANSACTIONNUMBER NUMBER not null
)
;
alter table nst_nasaka_SALE.SALEACCOUNTBRIDGE
  add constraint PKGDCTSLTRACTR primary key (GOODSCATEGORYCODE, SALETRANSACTIONNUMBER, ACCOUNTTRANSACTIONNUMBER);
alter table nst_nasaka_SALE.SALEACCOUNTBRIDGE
  add constraint FKACTRNNO foreign key (ACCOUNTTRANSACTIONNUMBER)
  references nst_nasaka_FINANCE.VOUCHERHEADER (TRANSACTIONNUMBER);
alter table nst_nasaka_SALE.SALEACCOUNTBRIDGE
  add constraint FKSLTRNNO foreign key (SALETRANSACTIONNUMBER)
  references nst_nasaka_SALE.SALEINVOICEHEADER (TRANSACTIONNUMBER);

prompt
prompt Creating table SALECONTROLTABLE
prompt ===============================
prompt
create table nst_nasaka_SALE.SALECONTROLTABLE
(
  GOODSCATEGORYCODE  NUMBER not null,
  DEBTORSACCOUNTCODE NUMBER not null,
  POSTINGCATEGORY    NUMBER not null,
  SALEACCOUNTCODE    NUMBER not null,
  CGSTACCOUNTCODE    NUMBER,
  SGSTACCOUNTCODE    NUMBER,
  IGSTACCOUNTCODE    NUMBER,
  UGSTACCOUNTCODE    NUMBER,
  VATACCOUNTCODE     NUMBER,
  GSTEXPENSES        NUMBER
)
;
alter table nst_nasaka_SALE.SALECONTROLTABLE
  add constraint PKGDCT primary key (GOODSCATEGORYCODE, POSTINGCATEGORY);

prompt
prompt Creating table SALEINVOICEDETAIL
prompt ================================
prompt
create table nst_nasaka_SALE.SALEINVOICEDETAIL
(
  TRANSACTIONNUMBER  NUMBER not null,
  SERIALNUMBER       NUMBER not null,
  FINISHEDGOODSCODE  NUMBER,
  PRODUCTIONYEARCODE NUMBER,
  SALEQUANTITY       NUMBER,
  SALERATE           NUMBER,
  AMOUNT             NUMBER,
  CGSTRATE           NUMBER,
  SGSTRATE           NUMBER,
  IGSTRATE           NUMBER,
  UGSTRATE           NUMBER,
  VATRATE            NUMBER,
  CGSTAMOUNT         NUMBER,
  SGSTAMOUNT         NUMBER,
  IGSTAMOUNT         NUMBER,
  UGSTAMOUNT         NUMBER,
  VATAMOUNT          NUMBER,
  TOTALTAXAMOUNT     NUMBER,
  GROSSAMOUNT        NUMBER,
  GODOWNNUMBER       NUMBER,
  LOTNUMBER          NUMBER
)
;
alter table nst_nasaka_SALE.SALEINVOICEDETAIL
  add constraint PK434 primary key (TRANSACTIONNUMBER, SERIALNUMBER);
alter table nst_nasaka_SALE.SALEINVOICEDETAIL
  add constraint FK76878 foreign key (TRANSACTIONNUMBER)
  references nst_nasaka_SALE.SALEINVOICEHEADER (TRANSACTIONNUMBER) on delete cascade;
alter table nst_nasaka_SALE.SALEINVOICEDETAIL
  add constraint FKFINGODCD foreign key (FINISHEDGOODSCODE)
  references nst_nasaka_SALE.FINISHEDGOODS (FINISHEDGOODSCODE);
alter table nst_nasaka_SALE.SALEINVOICEDETAIL
  add constraint FKGODNUM foreign key (GODOWNNUMBER)
  references nst_nasaka_SALE.GODOWN (GODOWNNUMBER);

prompt
prompt Creating table SALEORDERHEADER
prompt ==============================
prompt
create table nst_nasaka_SALE.SALEORDERHEADER
(
  TRANSACTIONNUMBER          NUMBER not null,
  SALEORDERNUMBER            NUMBER,
  SALEORDERNUMBERPRESUF      VARCHAR2(20),
  SALEORDERDATE              DATE,
  PURCHASERCODE              NUMBER,
  GOODSCATEGORYCODE          NUMBER,
  VALIDDATEOFLIFTING         DATE,
  YEARPERIODCODE             NUMBER,
  TENDERTRANSACTIONNUMBER    NUMBER,
  QUOTATIONTRANSACTIONNUMBER NUMBER,
  SALECATEGORYCODE           NUMBER,
  SALEORDERNUMBERSERIESID    NUMBER,
  SALEORDERNUMBERBASEVALUE   VARCHAR2(15),
  REMARK                     VARCHAR2(200)
)
;
alter table nst_nasaka_SALE.SALEORDERHEADER
  add constraint PKTRNNO primary key (TRANSACTIONNUMBER);
alter table nst_nasaka_SALE.SALEORDERHEADER
  add constraint FKPURCD1 foreign key (PURCHASERCODE, GOODSCATEGORYCODE)
  references nst_nasaka_SALE.GOODSPURCHASER (PURCHASERCODE, GOODSCATEGORYCODE);
alter table nst_nasaka_SALE.SALEORDERHEADER
  add constraint FKSALCAT foreign key (SALECATEGORYCODE)
  references nst_nasaka_SALE.SALECATEGORY (SALECATEGORYCODE);

prompt
prompt Creating table SALETENDERHEADER
prompt ===============================
prompt
create table nst_nasaka_SALE.SALETENDERHEADER
(
  TRANSACTIONNUMBER           NUMBER not null,
  YEARPERIODCODE              NUMBER not null,
  TENDERNUMBER                NUMBER not null,
  TENDERNUMBERPRESUF          VARCHAR2(20) not null,
  TENDERDATE                  DATE not null,
  PERMISSIONTRANSACTIONNUMBER NUMBER not null,
  GOODSCATEGORYCODE           NUMBER not null,
  SALECATEGORYCODE            NUMBER not null,
  VALIDDATEOFLIFTING          DATE not null,
  TENDERCLOSEDATE             DATE,
  TENDERNUMBERSERIESID        NUMBER not null,
  TENDERNUMBERBASEVALUE       VARCHAR2(15) not null,
  REMARK                      VARCHAR2(200)
)
;
alter table nst_nasaka_SALE.SALETENDERHEADER
  add constraint PKTRANENQUIRY primary key (TRANSACTIONNUMBER);
alter table nst_nasaka_SALE.SALETENDERHEADER
  add constraint FKGODCATCD6 foreign key (GOODSCATEGORYCODE)
  references nst_nasaka_SALE.GOODSCATEGORY (GOODSCATEGORYCODE);
alter table nst_nasaka_SALE.SALETENDERHEADER
  add constraint FKPER foreign key (PERMISSIONTRANSACTIONNUMBER)
  references nst_nasaka_SALE.GOODSSALEPERMISSION (TRANSACTIONNUMBER);
alter table nst_nasaka_SALE.SALETENDERHEADER
  add constraint FKSALECATCD2 foreign key (SALECATEGORYCODE)
  references nst_nasaka_SALE.SALECATEGORY (SALECATEGORYCODE);
alter table nst_nasaka_SALE.SALETENDERHEADER
  add constraint FKSALNUMSER3 foreign key (TENDERNUMBERSERIESID)
  references nst_nasaka_SALE.SALENUMBERSERIES (SALENUMBERSERIESID);
grant select on nst_nasaka_SALE.SALETENDERHEADER to nst_nasaka_DB;

prompt
prompt Creating table SALEMEMODETAIL
prompt =============================
prompt
create table nst_nasaka_SALE.SALEMEMODETAIL
(
  TRANSACTIONNUMBER       NUMBER not null,
  SERIALNUMBER            NUMBER not null,
  FINISHEDGOODSCODE       NUMBER,
  PRODUCTIONYEARCODE      NUMBER,
  SALEQUANTITY            NUMBER,
  SALERATE                NUMBER,
  AMOUNT                  NUMBER,
  CGSTRATE                NUMBER,
  SGSTRATE                NUMBER,
  IGSTRATE                NUMBER,
  UGSTRATE                NUMBER,
  VATRATE                 NUMBER,
  CGSTAMOUNT              NUMBER,
  SGSTAMOUNT              NUMBER,
  IGSTAMOUNT              NUMBER,
  UGSTAMOUNT              NUMBER,
  VATAMOUNT               NUMBER,
  TOTALTAXAMOUNT          NUMBER,
  GROSSAMOUNT             NUMBER,
  TENDERTRANSACTIONNUMBER NUMBER,
  ORDERTRANSACTIONNUMBER  NUMBER
)
;
alter table nst_nasaka_SALE.SALEMEMODETAIL
  add constraint PKSALEDEAT12 primary key (TRANSACTIONNUMBER, SERIALNUMBER);
alter table nst_nasaka_SALE.SALEMEMODETAIL
  add constraint FKFINGOODS foreign key (FINISHEDGOODSCODE)
  references nst_nasaka_SALE.FINISHEDGOODS (FINISHEDGOODSCODE);
alter table nst_nasaka_SALE.SALEMEMODETAIL
  add constraint FKORTRAN foreign key (ORDERTRANSACTIONNUMBER)
  references nst_nasaka_SALE.SALEORDERHEADER (TRANSACTIONNUMBER);
alter table nst_nasaka_SALE.SALEMEMODETAIL
  add constraint FKTENTRAN foreign key (TENDERTRANSACTIONNUMBER)
  references nst_nasaka_SALE.SALETENDERHEADER (TRANSACTIONNUMBER);
alter table nst_nasaka_SALE.SALEMEMODETAIL
  add constraint FKTRNNO foreign key (TRANSACTIONNUMBER)
  references nst_nasaka_SALE.SALEMEMOHEADER (TRANSACTIONNUMBER) on delete cascade;
grant select on nst_nasaka_SALE.SALEMEMODETAIL to nst_nasaka_DB;

prompt
prompt Creating table SALEORDERDETAIL
prompt ==============================
prompt
create table nst_nasaka_SALE.SALEORDERDETAIL
(
  TRANSACTIONNUMBER  NUMBER not null,
  SERIALNUMBER       NUMBER not null,
  FINISHEDGOODSCODE  NUMBER,
  PRODUCTIONYEARCODE NUMBER,
  ORDERQUANTITY      NUMBER,
  ORDERRATE          NUMBER
)
;
alter table nst_nasaka_SALE.SALEORDERDETAIL
  add constraint PKTRNSRNO1 primary key (TRANSACTIONNUMBER, SERIALNUMBER);
alter table nst_nasaka_SALE.SALEORDERDETAIL
  add constraint FKFINGODCD1 foreign key (FINISHEDGOODSCODE)
  references nst_nasaka_SALE.FINISHEDGOODS (FINISHEDGOODSCODE);
alter table nst_nasaka_SALE.SALEORDERDETAIL
  add constraint FKSOH foreign key (TRANSACTIONNUMBER)
  references nst_nasaka_SALE.SALEORDERHEADER (TRANSACTIONNUMBER) on delete cascade;

prompt
prompt Creating table SALEQUOTATIONHEADER
prompt ==================================
prompt
create table nst_nasaka_SALE.SALEQUOTATIONHEADER
(
  TRANSACTIONNUMBER        NUMBER not null,
  QUOTATIONNUMBER          NUMBER not null,
  QUOTATIONNUMBERPRESUF    VARCHAR2(20) not null,
  QUOTATIONDATE            DATE not null,
  VALIDDATEOFLIFTING       DATE not null,
  PURCHASERCODE            NUMBER not null,
  PURCHASERQUOTATIONNUMBER NUMBER,
  YEARPERIODCODE           NUMBER not null,
  GOODSCATEGORYCODE        NUMBER not null,
  SALECATEGORYCODE         NUMBER not null,
  TENDERTRANSACTIONNUMBER  NUMBER not null,
  QUOTATIONNUMBERSERIESID  NUMBER not null,
  QUOTATIONNUMBERBASEVALUE VARCHAR2(15) not null,
  REMARK                   VARCHAR2(200)
)
;
alter table nst_nasaka_SALE.SALEQUOTATIONHEADER
  add constraint PKSALETENDPK primary key (TRANSACTIONNUMBER);
alter table nst_nasaka_SALE.SALEQUOTATIONHEADER
  add constraint FKPURGDCT foreign key (PURCHASERCODE, GOODSCATEGORYCODE)
  references nst_nasaka_SALE.GOODSPURCHASER (PURCHASERCODE, GOODSCATEGORYCODE);
alter table nst_nasaka_SALE.SALEQUOTATIONHEADER
  add constraint FKQUOTNUM foreign key (QUOTATIONNUMBERSERIESID)
  references nst_nasaka_SALE.SALENUMBERSERIES (SALENUMBERSERIESID);
alter table nst_nasaka_SALE.SALEQUOTATIONHEADER
  add constraint FKSALCATCD4 foreign key (SALECATEGORYCODE)
  references nst_nasaka_SALE.SALECATEGORY (SALECATEGORYCODE);

prompt
prompt Creating table SALEQUOTATIONDETAIL
prompt ==================================
prompt
create table nst_nasaka_SALE.SALEQUOTATIONDETAIL
(
  TRANSACTIONNUMBER  NUMBER not null,
  SERIALNUMBER       NUMBER not null,
  FINISHEDGOODSCODE  NUMBER not null,
  PRODUCTIONYEARCODE NUMBER not null,
  QUOTATIONQUANTITY  NUMBER,
  QUOTATIONRATE      NUMBER
)
;
alter table nst_nasaka_SALE.SALEQUOTATIONDETAIL
  add constraint PGSDETAILPK primary key (TRANSACTIONNUMBER, SERIALNUMBER);
alter table nst_nasaka_SALE.SALEQUOTATIONDETAIL
  add constraint FKFINGODCD2 foreign key (FINISHEDGOODSCODE)
  references nst_nasaka_SALE.FINISHEDGOODS (FINISHEDGOODSCODE);
alter table nst_nasaka_SALE.SALEQUOTATIONDETAIL
  add constraint FKTRNHEA foreign key (TRANSACTIONNUMBER)
  references nst_nasaka_SALE.SALEQUOTATIONHEADER (TRANSACTIONNUMBER) on delete cascade;

prompt
prompt Creating table SALETENDERDETAIL
prompt ===============================
prompt
create table nst_nasaka_SALE.SALETENDERDETAIL
(
  TRANSACTIONNUMBER  NUMBER not null,
  SERIALNUMBER       NUMBER not null,
  FINISHEDGOODSCODE  NUMBER not null,
  PRODUCTIONYEARCODE NUMBER not null,
  TENDERQUANTITY     NUMBER
)
;
alter table nst_nasaka_SALE.SALETENDERDETAIL
  add constraint PKSEDT primary key (TRANSACTIONNUMBER, SERIALNUMBER);
alter table nst_nasaka_SALE.SALETENDERDETAIL
  add constraint UNQTRNFINPROD unique (TRANSACTIONNUMBER, FINISHEDGOODSCODE, PRODUCTIONYEARCODE);
alter table nst_nasaka_SALE.SALETENDERDETAIL
  add constraint FKFINGOD5 foreign key (FINISHEDGOODSCODE)
  references nst_nasaka_SALE.FINISHEDGOODS (FINISHEDGOODSCODE);
alter table nst_nasaka_SALE.SALETENDERDETAIL
  add constraint TRNTENDHE foreign key (TRANSACTIONNUMBER)
  references nst_nasaka_SALE.SALETENDERHEADER (TRANSACTIONNUMBER) on delete cascade;
grant select on nst_nasaka_SALE.SALETENDERDETAIL to nst_nasaka_DB;

prompt
prompt Creating view BROKERWISELIFTEDUNLIFTED
prompt ======================================
prompt
create or replace view nst_nasaka_sale.brokerwiseliftedunlifted as
select a.brokercode
,a.tendertransactionnumber
,a.ordertransactionnumber
,a.tendernumberpresuf
,a.saleordernumberpresuf
,a.validdateoflifting
,a.finishedgoodscode
,a.finishedgoodsnameuni
,a.finishedgoodsnameeng
,a.productionyearcode
,a.orderrate
,sum(a.orderquantity)orderquantity
,sum(a.salequantity) salequantity
,nvl(sum(a.orderquantity),0)- nvl(sum(a.salequantity),0)  balancequantity
  from (select h.purchasercode brokercode
  ,t.transactionnumber tendertransactionnumber
  ,h.transactionnumber ordertransactionnumber
  , t.tendernumberpresuf,h.saleordernumberpresuf,
  h.validdateoflifting
  ,d.finishedgoodscode
  ,f.finishedgoodsnameuni
  ,f.finishedgoodsnameeng,
  d.productionyearcode,d.orderrate,d.orderquantity,0 salequantity
 from saleorderdetail d,saleorderheader h,saletenderheader t,
 finishedgoods f
where d.transactionnumber=h.transactionnumber
and d.finishedgoodscode=f.finishedgoodscode
and h.tendertransactionnumber= t.transactionnumber
union all
select  h.brokercode brokercode
,d.tendertransactionnumber
,d.ordertransactionnumber
, t.tendernumberpresuf,o.saleordernumberpresuf,
o.validdateoflifting
,d.finishedgoodscode
,f.finishedgoodsnameuni,f.finishedgoodsnameeng,
  d.productionyearcode,d.salerate orderrate,0 orderquantity, d.salequantity
  from salememodetail d,salememoheader h,saletenderheader t,finishedgoods f,saleorderheader o
where d.transactionnumber=h.transactionnumber
and  d.tendertransactionnumber = t.transactionnumber
and d.finishedgoodscode= f.finishedgoodscode
and d.ordertransactionnumber = o.transactionnumber) a
group by  a.brokercode
,a.tendertransactionnumber
,a.ordertransactionnumber
, a.tendernumberpresuf,a.saleordernumberpresuf,a.validdateoflifting
,a.finishedgoodscode
,a.finishedgoodsnameuni,a.finishedgoodsnameeng,
  a.productionyearcode,a.orderrate;

prompt
prompt Creating view MEMOBALANCE
prompt =========================
prompt
create or replace view nst_nasaka_sale.memobalance as
select a.transactionnumber as salememotransactionnumber
,m.memonumberpresuf
,a.finishedgoodscode
,a.finishedgoodsnameuni
,a.finishedgoodsnameeng
,a.productionyearcode
,a.salerate
,sum(a.salequantity) balancequantity
from
(select h.transactionnumber
,d.finishedgoodscode
,g.finishedgoodsnameuni
,g.finishedgoodsnameeng
,d.productionyearcode
,d.salerate
,sum(d.salequantity) salequantity
from salememodetail d,salememoheader h,finishedgoods g
where d.transactionnumber =h.transactionnumber
and d.finishedgoodscode =g.finishedgoodscode
group by h.transactionnumber
,d.finishedgoodscode
,g.finishedgoodsnameuni
,g.finishedgoodsnameeng
,d.productionyearcode
,d.salerate
union all
select h.salememotransactionnumber transactionnumber
, d.finishedgoodscode
,g.finishedgoodsnameuni
,g.finishedgoodsnameeng
,d.productionyearcode
,d.salerate
,sum(d.salequantity * -1) salequantity
from saleinvoicedetail d,saleinvoiceheader h,finishedgoods g
where d.transactionnumber =h.transactionnumber
and d.finishedgoodscode =g.finishedgoodscode
group by h.salememotransactionnumber
, d.finishedgoodscode
,g.finishedgoodsnameuni
,g.finishedgoodsnameeng
,d.productionyearcode
,d.salerate) a,salememoheader m
where a.transactionnumber=m.transactionnumber
group by
a.transactionnumber
,m.memonumberpresuf
,a.finishedgoodscode
,a.finishedgoodsnameuni
,a.finishedgoodsnameeng
,a.productionyearcode
,a.salerate;

prompt
prompt Creating view VW_PENDINGMEMOFORINVOICE
prompt ======================================
prompt
create or replace view nst_nasaka_sale.vw_pendingmemoforinvoice as
select s.transactionnumber,s.memonumberpresuf
from
(select t.transactionnumber as transactionnumber,d.finishedgoodscode,sum(d.salequantity) as salequantity
from salememoheader t,salememodetail d
where t.transactionnumber=d.transactionnumber
group by t.transactionnumber,d.finishedgoodscode
union all
select h.salememotransactionnumber as transactionnumber,d.finishedgoodscode,sum(d.salequantity)*-1 as salequantity
from saleinvoiceheader h,saleinvoicedetail d
where h.transactionnumber=d.transactionnumber
group by h.salememotransactionnumber,d.finishedgoodscode)t,salememoheader s
where t.transactionnumber=s.transactionnumber
group by s.transactionnumber,s.memonumberpresuf
having sum(salequantity)>0;

prompt
prompt Creating function SALENUMBERBASEVALUE
prompt =====================================
prompt
create or replace function nst_nasaka_sale.salenumberbasevalue(pdate in date,presetcategoryid in number default 4) return varchar2 is
begin
declare
dy number;
mth number;
qtr number;
yr number;
begin
    dy:=to_number(to_char(pdate,'dd'));
    mth:=to_number(to_char(pdate,'mm'));
    yr:=to_number(to_char(pdate,'yyyy'));
    if presetcategoryid =1 then
      return 'D'||trim(to_char(dy,'00'))||trim(to_char(mth,'00'))||yr;
    else if presetcategoryid =2 then
      return 'M'||trim(to_char(mth,'00'))||yr;
    else if presetcategoryid =3 then
      if mth>=4 and mth<=6 then
         qtr:=1;
      else if mth>=7 and mth<=9 then
         qtr:=2;
      else if mth>=10 and mth<=12 then
         qtr:=3;
      else if mth>=1 and mth<=3 then
         qtr:=4;
      end if;
      end if;
      end if;
      end if;
      return 'Q'||'0'||qtr||yr;
    else if presetcategoryid =4 then
      if mth>=4 and mth<=12 then
         return 'Y'||yr||to_char(yr+1);
      else
         return 'Y'||to_char(yr-1)||yr;
      end if;
    end if;
    end if;
    end if;
    end if;
end;
end salenumberbasevalue;
/

prompt
prompt Creating procedure SALEINVOICEHEADER_UPDATE
prompt ===========================================
prompt
create or replace procedure nst_nasaka_sale.saleinvoiceheader_update(p_transactionnumber in number) is
begin
declare
mamount number;
mcgstamount number;
msgstamount number;
migstamount number;
mugstamount number;
mvatamount number;
mtotaltaxamount number;
mgrossamount number;

cursor detail is
select sum(d.amount),sum(d.cgstamount),sum(d.sgstamount),sum(d.igstamount),
sum(d.ugstamount),sum(d.vatamount),sum(d.totaltaxamount),sum(d.grossamount)
from saleinvoicedetail d
where d.transactionnumber=p_transactionnumber;

begin
open detail;
fetch detail into mamount,mcgstamount,msgstamount,migstamount,mugstamount,
mvatamount,mtotaltaxamount,mgrossamount;
close detail;
update saleinvoiceheader t
set t.cgstamount=mcgstamount,
    t.sgstamount=msgstamount,
    t.igstamount=igstamount,
    t.ugstamount=mugstamount,
    t.vatamount=mvatamount,
    t.totaltaxamount=mtotaltaxamount,
    t.grossamount=mgrossamount
    where t.transactionnumber=p_transactionnumber;
    commit;
end;
end saleinvoiceheader_update;
/

prompt
prompt Creating procedure SALEINVOICEVOUCHERPOSTING
prompt ============================================
prompt
create or replace procedure nst_nasaka_sale.saleinvoicevoucherposting(p_goodscategorycode in number,p_postingcategory in number,p_flag in varchar2 default 'N',p_transactionnumber in number) is
begin
declare

m_debtorsaccountcode number;
m_saleaccountcode number;
m_cgstaccountcode number;
m_sgstaccountcode number;
m_igstaccountcode number;
m_ugstaccountcode number;
m_vataccountcode number;
m_gstexpenses number;

cursor control is
select s.debtorsaccountcode
,s.saleaccountcode
,s.cgstaccountcode
,s.sgstaccountcode
,s.igstaccountcode
,s.ugstaccountcode
,s.vataccountcode
,s.gstexpenses
from salecontroltable s
where s.goodscategorycode=p_goodscategorycode
and s.postingcategory=p_postingcategory;

begin
    open control;
    fetch control into m_debtorsaccountcode
    ,m_saleaccountcode
    ,m_cgstaccountcode
    ,m_sgstaccountcode
    ,m_igstaccountcode
    ,m_ugstaccountcode
    ,m_vataccountcode
    ,m_gstexpenses;
    close control;
    --posting category 1 for basic sale amount posting and flag N for new
    if (p_postingcategory=1 and p_flag='N') then
       insert into saleaccountbridge
       (goodscategorycode,saletransactionnumber)
       values (p_goodscategorycode,p_transactionnumber);
       commit;
    end if;
    commit;
end;
end saleinvoicevoucherposting;
/

prompt
prompt Creating procedure SALEMEMOHEADER_UPDATE
prompt ========================================
prompt
create or replace procedure nst_nasaka_sale.salememoheader_update(p_transactionnumber in number) is
begin
declare
mamount number;
mcgstamount number;
msgstamount number;
migstamount number;
mugstamount number;
mvatamount number;
mtotaltaxamount number;
mgrossamount number;

cursor detail is
select sum(d.amount),sum(d.cgstamount),sum(d.sgstamount),sum(d.igstamount),
sum(d.ugstamount),sum(d.vatamount),sum(d.totaltaxamount),sum(d.grossamount)
from salememodetail d
where d.transactionnumber=p_transactionnumber;

begin
open detail;
fetch detail into mamount,mcgstamount,msgstamount,migstamount,mugstamount,
mvatamount,mtotaltaxamount,mgrossamount;
close detail;
update salememoheader t
set t.cgstamount=mcgstamount,
    t.sgstamount=msgstamount,
    t.igstamount=igstamount,
    t.ugstamount=mugstamount,
    t.vatamount=mvatamount,
    t.totaltaxamount=mtotaltaxamount,
    t.grossamount=mgrossamount
    where t.transactionnumber=p_transactionnumber;
    commit;
end;
end salememoheader_update;
/


spool off
