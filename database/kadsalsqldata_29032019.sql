prompt PL/SQL Developer import file
prompt Created on 29 March 2019 by admin
set feedback off
set define off
prompt Creating GOODSCATEGORY...
create table GOODSCATEGORY
(
  GOODSCATEGORYCODE    NUMBER not null,
  GOODSCATEGORYNAMEUNI VARCHAR2(100),
  GOODSCATEGORYNAMEENG VARCHAR2(100)
)
tablespace USERS
  pctfree 10
  initrans 1
  maxtrans 255
  storage
  (
    initial 64K
    next 1M
    minextents 1
    maxextents unlimited
  );
alter table GOODSCATEGORY
  add constraint PKSCC primary key (GOODSCATEGORYCODE)
  using index 
  tablespace USERS
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 64K
    next 1M
    minextents 1
    maxextents unlimited
  );

prompt Creating GOODSSUBCATEGORY...
create table GOODSSUBCATEGORY
(
  SUBCATEGORYCODE    NUMBER not null,
  SUBCATEGORYNAMEUNI VARCHAR2(100),
  SUBCATEGORYNAMEENG VARCHAR2(100),
  CATEGORYCODE       NUMBER
)
tablespace USERS
  pctfree 10
  initrans 1
  maxtrans 255
  storage
  (
    initial 64K
    next 1M
    minextents 1
    maxextents unlimited
  );
alter table GOODSSUBCATEGORY
  add constraint PKSCSC primary key (SUBCATEGORYCODE)
  using index 
  tablespace USERS
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 64K
    next 1M
    minextents 1
    maxextents unlimited
  );
alter table GOODSSUBCATEGORY
  add constraint FKCATCODE foreign key (CATEGORYCODE)
  references GOODSCATEGORY (GOODSCATEGORYCODE);

prompt Creating FINISHEDGOODS...
create table FINISHEDGOODS
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
tablespace USERS
  pctfree 10
  initrans 1
  maxtrans 255
  storage
  (
    initial 64K
    next 1M
    minextents 1
    maxextents unlimited
  );
alter table FINISHEDGOODS
  add constraint PKGOODSSALE primary key (FINISHEDGOODSCODE)
  using index 
  tablespace USERS
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 64K
    next 1M
    minextents 1
    maxextents unlimited
  );
alter table FINISHEDGOODS
  add constraint FKGSUBCAT foreign key (SUBCATEGORYCODE)
  references GOODSSUBCATEGORY (SUBCATEGORYCODE);

prompt Creating GODOWN...
create table GODOWN
(
  GODOWNNUMBER       NUMBER not null,
  GODOWNNAMEENG      VARCHAR2(50),
  GODOWNNAMEUNI      VARCHAR2(50),
  GODOWNCATEGORYCODE NUMBER,
  GODOWNCAPACITY     NUMBER
)
tablespace USERS
  pctfree 10
  initrans 1
  maxtrans 255
  storage
  (
    initial 64K
    next 1M
    minextents 1
    maxextents unlimited
  );
alter table GODOWN
  add constraint PKGODCODE primary key (GODOWNNUMBER)
  using index 
  tablespace USERS
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 64K
    next 1M
    minextents 1
    maxextents unlimited
  );

prompt Creating PURCHASERCATEGORY...
create table PURCHASERCATEGORY
(
  PURCHASERCATEGORYCODE    NUMBER not null,
  PURCHASERCATEGORYNAMEUNI VARCHAR2(200),
  PURCHASERCATEGORYNAMEENG VARCHAR2(200)
)
tablespace USERS
  pctfree 10
  initrans 1
  maxtrans 255
  storage
  (
    initial 64K
    next 1M
    minextents 1
    maxextents unlimited
  );
alter table PURCHASERCATEGORY
  add constraint PCATCD primary key (PURCHASERCATEGORYCODE)
  using index 
  tablespace USERS
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 64K
    next 1M
    minextents 1
    maxextents unlimited
  );

prompt Creating GOODSPURCHASER...
create table GOODSPURCHASER
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
tablespace USERS
  pctfree 10
  initrans 1
  maxtrans 255
  storage
  (
    initial 64K
    next 1M
    minextents 1
    maxextents unlimited
  );
alter table GOODSPURCHASER
  add constraint GOODSPURCHASERPK primary key (PURCHASERCODE, GOODSCATEGORYCODE)
  using index 
  tablespace USERS
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 64K
    next 1M
    minextents 1
    maxextents unlimited
  );
alter table GOODSPURCHASER
  add constraint FKGOODSPURCHASECD foreign key (PURCHASERCATEGORYCODE)
  references PURCHASERCATEGORY (PURCHASERCATEGORYCODE);
grant select on GOODSPURCHASER to nst_nasaka_DB;

prompt Creating GOODSSALEPERMISSION...
create table GOODSSALEPERMISSION
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
tablespace USERS
  pctfree 10
  initrans 1
  maxtrans 255
  storage
  (
    initial 64K
    next 1M
    minextents 1
    maxextents unlimited
  );
alter table GOODSSALEPERMISSION
  add constraint PKTRNNO1 primary key (TRANSACTIONNUMBER)
  using index 
  tablespace USERS
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 64K
    next 1M
    minextents 1
    maxextents unlimited
  );
grant select, insert on GOODSSALEPERMISSION to nst_nasaka_DB;

prompt Creating GOODSSALETAXES...
create table GOODSSALETAXES
(
  TAXCODE NUMBER not null,
  TAXNAME VARCHAR2(100)
)
tablespace USERS
  pctfree 10
  initrans 1
  maxtrans 255
  storage
  (
    initial 64K
    next 1M
    minextents 1
    maxextents unlimited
  );
alter table GOODSSALETAXES
  add constraint PKTAXM primary key (TAXCODE)
  using index 
  tablespace USERS
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 64K
    next 1M
    minextents 1
    maxextents unlimited
  );

prompt Creating SALECATEGORY...
create table SALECATEGORY
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
tablespace USERS
  pctfree 10
  initrans 1
  maxtrans 255
  storage
  (
    initial 64K
    next 1M
    minextents 1
    maxextents unlimited
  );
alter table SALECATEGORY
  add constraint PKSALECATECDS primary key (SALECATEGORYCODE)
  using index 
  tablespace USERS
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 64K
    next 1M
    minextents 1
    maxextents unlimited
  );

prompt Creating GOODSTAXRATE...
create table GOODSTAXRATE
(
  TRANSACTIONNUMBER NUMBER not null,
  FINISHEDGOODSCODE NUMBER,
  TAXCODE           NUMBER,
  FROMDATE          DATE,
  TODATE            DATE,
  TAXPERCENT        NUMBER,
  SALECATEGORYCODE  NUMBER
)
tablespace USERS
  pctfree 10
  initrans 1
  maxtrans 255
  storage
  (
    initial 64K
    next 1M
    minextents 1
    maxextents unlimited
  );
alter table GOODSTAXRATE
  add constraint PKTAXRATETRANSACTIO primary key (TRANSACTIONNUMBER)
  using index 
  tablespace USERS
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 64K
    next 1M
    minextents 1
    maxextents unlimited
  );
alter table GOODSTAXRATE
  add constraint FK112 foreign key (TAXCODE)
  references GOODSSALETAXES (TAXCODE);
alter table GOODSTAXRATE
  add constraint FKSALCATCD foreign key (SALECATEGORYCODE)
  references SALECATEGORY (SALECATEGORYCODE);

prompt Creating PERIODRESETCATEGORY...
create table PERIODRESETCATEGORY
(
  PERIODRESETCATEGORYCODE NUMBER not null,
  PERIODRESETCATEGORYNAME VARCHAR2(100)
)
tablespace USERS
  pctfree 10
  initrans 1
  maxtrans 255
  storage
  (
    initial 64K
    next 1M
    minextents 1
    maxextents unlimited
  );
alter table PERIODRESETCATEGORY
  add constraint PKPERIODRESETCATEGORID primary key (PERIODRESETCATEGORYCODE)
  using index 
  tablespace USERS
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 64K
    next 1M
    minextents 1
    maxextents unlimited
  );

prompt Creating SALENUMBERSERIES...
create table SALENUMBERSERIES
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
tablespace USERS
  pctfree 10
  initrans 1
  maxtrans 255
  storage
  (
    initial 64K
    next 1M
    minextents 1
    maxextents unlimited
  );
alter table SALENUMBERSERIES
  add constraint PKSALENUMBERSERIESID primary key (SALENUMBERSERIESID)
  using index 
  tablespace USERS
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 64K
    next 1M
    minextents 1
    maxextents unlimited
  );
alter table SALENUMBERSERIES
  add constraint FKGODCATCD foreign key (GOODSCATEGORYCODE)
  references GOODSCATEGORY (GOODSCATEGORYCODE);
alter table SALENUMBERSERIES
  add constraint FKPERRESCAT foreign key (PERIODRESETCATEGORYCODE)
  references PERIODRESETCATEGORY (PERIODRESETCATEGORYCODE);

prompt Creating SALEMEMOHEADER...
create table SALEMEMOHEADER
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
tablespace USERS
  pctfree 10
  initrans 1
  maxtrans 255
  storage
  (
    initial 64K
    next 1M
    minextents 1
    maxextents unlimited
  );
alter table SALEMEMOHEADER
  add constraint PKSALE primary key (TRANSACTIONNUMBER)
  using index 
  tablespace USERS
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 64K
    next 1M
    minextents 1
    maxextents unlimited
  );
alter table SALEMEMOHEADER
  add constraint FKBRGODCAT foreign key (BROKERCODE, GOODSCATEGORYCODE)
  references GOODSPURCHASER (PURCHASERCODE, GOODSCATEGORYCODE);
alter table SALEMEMOHEADER
  add constraint FKMENS foreign key (SALEMEMONUMBERSERIESID)
  references SALENUMBERSERIES (SALENUMBERSERIESID);
alter table SALEMEMOHEADER
  add constraint FKPURGODCAT foreign key (PURCHASERCODE, GOODSCATEGORYCODE)
  references GOODSPURCHASER (PURCHASERCODE, GOODSCATEGORYCODE);
alter table SALEMEMOHEADER
  add constraint FKSHIPGODCAT foreign key (SHIPPINGPARTYCODE, GOODSCATEGORYCODE)
  references GOODSPURCHASER (PURCHASERCODE, GOODSCATEGORYCODE);
grant select on SALEMEMOHEADER to nst_nasaka_DB;

prompt Creating SALEINVOICEHEADER...
create table SALEINVOICEHEADER
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
tablespace USERS
  pctfree 10
  initrans 1
  maxtrans 255
  storage
  (
    initial 64K
    next 1M
    minextents 1
    maxextents unlimited
  );
alter table SALEINVOICEHEADER
  add constraint PKINVOCE primary key (TRANSACTIONNUMBER)
  using index 
  tablespace USERS
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 64K
    next 1M
    minextents 1
    maxextents unlimited
  );
alter table SALEINVOICEHEADER
  add constraint FKBRGC foreign key (BROKERCODE, GOODSCATEGORYCODE)
  references GOODSPURCHASER (PURCHASERCODE, GOODSCATEGORYCODE);
alter table SALEINVOICEHEADER
  add constraint FKGOODSCAT foreign key (GOODSCATEGORYCODE)
  references GOODSCATEGORY (GOODSCATEGORYCODE);
alter table SALEINVOICEHEADER
  add constraint FKNUMSERID foreign key (SALEINVOICENUMBERSERIESID)
  references SALENUMBERSERIES (SALENUMBERSERIESID);
alter table SALEINVOICEHEADER
  add constraint FKSALECAT foreign key (SALECATEGORYCODE)
  references SALECATEGORY (SALECATEGORYCODE);
alter table SALEINVOICEHEADER
  add constraint FKSALEMEMOTRNNUM foreign key (SALEMEMOTRANSACTIONNUMBER)
  references SALEMEMOHEADER (TRANSACTIONNUMBER);
create unique index UNIQ_COL1_COL2 on SALEINVOICEHEADER (CASE  WHEN (SALEINVOICENUMBERBASEVALUE IS NOT NULL AND INVOICENUMBER IS NOT NULL) THEN SALEINVOICENUMBERBASEVALUE END, CASE  WHEN (SALEINVOICENUMBERBASEVALUE IS NOT NULL AND INVOICENUMBER IS NOT NULL) THEN INVOICENUMBER END)
  tablespace USERS
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 64K
    next 1M
    minextents 1
    maxextents unlimited
  );

prompt Creating SALEACCOUNTBRIDGE...
create table SALEACCOUNTBRIDGE
(
  GOODSCATEGORYCODE        NUMBER not null,
  SALETRANSACTIONNUMBER    NUMBER not null,
  ACCOUNTTRANSACTIONNUMBER NUMBER not null
)
tablespace USERS
  pctfree 10
  initrans 1
  maxtrans 255
  storage
  (
    initial 64K
    next 1M
    minextents 1
    maxextents unlimited
  );
alter table SALEACCOUNTBRIDGE
  add constraint PKGDCTSLTRACTR primary key (GOODSCATEGORYCODE, SALETRANSACTIONNUMBER, ACCOUNTTRANSACTIONNUMBER)
  using index 
  tablespace USERS
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 64K
    next 1M
    minextents 1
    maxextents unlimited
  );
alter table SALEACCOUNTBRIDGE
  add constraint FKACTRNNO foreign key (ACCOUNTTRANSACTIONNUMBER)
  references nst_nasaka_FINANCE.VOUCHERHEADER (TRANSACTIONNUMBER);
alter table SALEACCOUNTBRIDGE
  add constraint FKSLTRNNO foreign key (SALETRANSACTIONNUMBER)
  references SALEINVOICEHEADER (TRANSACTIONNUMBER);

prompt Creating SALECONTROLTABLE...
create table SALECONTROLTABLE
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
tablespace USERS
  pctfree 10
  initrans 1
  maxtrans 255
  storage
  (
    initial 64K
    next 1M
    minextents 1
    maxextents unlimited
  );
alter table SALECONTROLTABLE
  add constraint PKGDCT primary key (GOODSCATEGORYCODE, POSTINGCATEGORY)
  using index 
  tablespace USERS
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 64K
    next 1M
    minextents 1
    maxextents unlimited
  );

prompt Creating SALEINVOICEDETAIL...
create table SALEINVOICEDETAIL
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
tablespace USERS
  pctfree 10
  initrans 1
  maxtrans 255
  storage
  (
    initial 64K
    next 1M
    minextents 1
    maxextents unlimited
  );
alter table SALEINVOICEDETAIL
  add constraint PK434 primary key (TRANSACTIONNUMBER, SERIALNUMBER)
  using index 
  tablespace USERS
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 64K
    next 1M
    minextents 1
    maxextents unlimited
  );
alter table SALEINVOICEDETAIL
  add constraint FK76878 foreign key (TRANSACTIONNUMBER)
  references SALEINVOICEHEADER (TRANSACTIONNUMBER) on delete cascade;
alter table SALEINVOICEDETAIL
  add constraint FKFINGODCD foreign key (FINISHEDGOODSCODE)
  references FINISHEDGOODS (FINISHEDGOODSCODE);
alter table SALEINVOICEDETAIL
  add constraint FKGODNUM foreign key (GODOWNNUMBER)
  references GODOWN (GODOWNNUMBER);

prompt Creating SALEORDERHEADER...
create table SALEORDERHEADER
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
tablespace USERS
  pctfree 10
  initrans 1
  maxtrans 255
  storage
  (
    initial 64K
    next 1M
    minextents 1
    maxextents unlimited
  );
alter table SALEORDERHEADER
  add constraint PKTRNNO primary key (TRANSACTIONNUMBER)
  using index 
  tablespace USERS
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 64K
    next 1M
    minextents 1
    maxextents unlimited
  );
alter table SALEORDERHEADER
  add constraint FKPURCD1 foreign key (PURCHASERCODE, GOODSCATEGORYCODE)
  references GOODSPURCHASER (PURCHASERCODE, GOODSCATEGORYCODE);
alter table SALEORDERHEADER
  add constraint FKSALCAT foreign key (SALECATEGORYCODE)
  references SALECATEGORY (SALECATEGORYCODE);

prompt Creating SALETENDERHEADER...
create table SALETENDERHEADER
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
tablespace USERS
  pctfree 10
  initrans 1
  maxtrans 255
  storage
  (
    initial 64K
    next 1M
    minextents 1
    maxextents unlimited
  );
alter table SALETENDERHEADER
  add constraint PKTRANENQUIRY primary key (TRANSACTIONNUMBER)
  using index 
  tablespace USERS
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 64K
    next 1M
    minextents 1
    maxextents unlimited
  );
alter table SALETENDERHEADER
  add constraint FKGODCATCD6 foreign key (GOODSCATEGORYCODE)
  references GOODSCATEGORY (GOODSCATEGORYCODE);
alter table SALETENDERHEADER
  add constraint FKPER foreign key (PERMISSIONTRANSACTIONNUMBER)
  references GOODSSALEPERMISSION (TRANSACTIONNUMBER);
alter table SALETENDERHEADER
  add constraint FKSALECATCD2 foreign key (SALECATEGORYCODE)
  references SALECATEGORY (SALECATEGORYCODE);
alter table SALETENDERHEADER
  add constraint FKSALNUMSER3 foreign key (TENDERNUMBERSERIESID)
  references SALENUMBERSERIES (SALENUMBERSERIESID);
grant select on SALETENDERHEADER to nst_nasaka_DB;

prompt Creating SALEMEMODETAIL...
create table SALEMEMODETAIL
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
tablespace USERS
  pctfree 10
  initrans 1
  maxtrans 255
  storage
  (
    initial 64K
    next 1M
    minextents 1
    maxextents unlimited
  );
alter table SALEMEMODETAIL
  add constraint PKSALEDEAT12 primary key (TRANSACTIONNUMBER, SERIALNUMBER)
  using index 
  tablespace USERS
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 64K
    next 1M
    minextents 1
    maxextents unlimited
  );
alter table SALEMEMODETAIL
  add constraint FKFINGOODS foreign key (FINISHEDGOODSCODE)
  references FINISHEDGOODS (FINISHEDGOODSCODE);
alter table SALEMEMODETAIL
  add constraint FKORTRAN foreign key (ORDERTRANSACTIONNUMBER)
  references SALEORDERHEADER (TRANSACTIONNUMBER);
alter table SALEMEMODETAIL
  add constraint FKTENTRAN foreign key (TENDERTRANSACTIONNUMBER)
  references SALETENDERHEADER (TRANSACTIONNUMBER);
alter table SALEMEMODETAIL
  add constraint FKTRNNO foreign key (TRANSACTIONNUMBER)
  references SALEMEMOHEADER (TRANSACTIONNUMBER) on delete cascade;
grant select on SALEMEMODETAIL to nst_nasaka_DB;

prompt Creating SALEORDERDETAIL...
create table SALEORDERDETAIL
(
  TRANSACTIONNUMBER  NUMBER not null,
  SERIALNUMBER       NUMBER not null,
  FINISHEDGOODSCODE  NUMBER,
  PRODUCTIONYEARCODE NUMBER,
  ORDERQUANTITY      NUMBER,
  ORDERRATE          NUMBER
)
tablespace USERS
  pctfree 10
  initrans 1
  maxtrans 255
  storage
  (
    initial 64K
    next 1M
    minextents 1
    maxextents unlimited
  );
alter table SALEORDERDETAIL
  add constraint PKTRNSRNO1 primary key (TRANSACTIONNUMBER, SERIALNUMBER)
  using index 
  tablespace USERS
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 64K
    next 1M
    minextents 1
    maxextents unlimited
  );
alter table SALEORDERDETAIL
  add constraint FKFINGODCD1 foreign key (FINISHEDGOODSCODE)
  references FINISHEDGOODS (FINISHEDGOODSCODE);
alter table SALEORDERDETAIL
  add constraint FKSOH foreign key (TRANSACTIONNUMBER)
  references SALEORDERHEADER (TRANSACTIONNUMBER) on delete cascade;

prompt Creating SALEQUOTATIONHEADER...
create table SALEQUOTATIONHEADER
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
tablespace USERS
  pctfree 10
  initrans 1
  maxtrans 255
  storage
  (
    initial 64K
    next 1M
    minextents 1
    maxextents unlimited
  );
alter table SALEQUOTATIONHEADER
  add constraint PKSALETENDPK primary key (TRANSACTIONNUMBER)
  using index 
  tablespace USERS
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 64K
    next 1M
    minextents 1
    maxextents unlimited
  );
alter table SALEQUOTATIONHEADER
  add constraint FKPURGDCT foreign key (PURCHASERCODE, GOODSCATEGORYCODE)
  references GOODSPURCHASER (PURCHASERCODE, GOODSCATEGORYCODE);
alter table SALEQUOTATIONHEADER
  add constraint FKQUOTNUM foreign key (QUOTATIONNUMBERSERIESID)
  references SALENUMBERSERIES (SALENUMBERSERIESID);
alter table SALEQUOTATIONHEADER
  add constraint FKSALCATCD4 foreign key (SALECATEGORYCODE)
  references SALECATEGORY (SALECATEGORYCODE);

prompt Creating SALEQUOTATIONDETAIL...
create table SALEQUOTATIONDETAIL
(
  TRANSACTIONNUMBER  NUMBER not null,
  SERIALNUMBER       NUMBER not null,
  FINISHEDGOODSCODE  NUMBER not null,
  PRODUCTIONYEARCODE NUMBER not null,
  QUOTATIONQUANTITY  NUMBER,
  QUOTATIONRATE      NUMBER
)
tablespace USERS
  pctfree 10
  initrans 1
  maxtrans 255
  storage
  (
    initial 64K
    next 1M
    minextents 1
    maxextents unlimited
  );
alter table SALEQUOTATIONDETAIL
  add constraint PGSDETAILPK primary key (TRANSACTIONNUMBER, SERIALNUMBER)
  using index 
  tablespace USERS
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 64K
    next 1M
    minextents 1
    maxextents unlimited
  );
alter table SALEQUOTATIONDETAIL
  add constraint FKFINGODCD2 foreign key (FINISHEDGOODSCODE)
  references FINISHEDGOODS (FINISHEDGOODSCODE);
alter table SALEQUOTATIONDETAIL
  add constraint FKTRNHEA foreign key (TRANSACTIONNUMBER)
  references SALEQUOTATIONHEADER (TRANSACTIONNUMBER) on delete cascade;

prompt Creating SALETENDERDETAIL...
create table SALETENDERDETAIL
(
  TRANSACTIONNUMBER  NUMBER not null,
  SERIALNUMBER       NUMBER not null,
  FINISHEDGOODSCODE  NUMBER not null,
  PRODUCTIONYEARCODE NUMBER not null,
  TENDERQUANTITY     NUMBER
)
tablespace USERS
  pctfree 10
  initrans 1
  maxtrans 255
  storage
  (
    initial 64K
    next 1M
    minextents 1
    maxextents unlimited
  );
alter table SALETENDERDETAIL
  add constraint PKSEDT primary key (TRANSACTIONNUMBER, SERIALNUMBER)
  using index 
  tablespace USERS
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 64K
    next 1M
    minextents 1
    maxextents unlimited
  );
alter table SALETENDERDETAIL
  add constraint UNQTRNFINPROD unique (TRANSACTIONNUMBER, FINISHEDGOODSCODE, PRODUCTIONYEARCODE)
  using index 
  tablespace USERS
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 64K
    next 1M
    minextents 1
    maxextents unlimited
  );
alter table SALETENDERDETAIL
  add constraint FKFINGOD5 foreign key (FINISHEDGOODSCODE)
  references FINISHEDGOODS (FINISHEDGOODSCODE);
alter table SALETENDERDETAIL
  add constraint TRNTENDHE foreign key (TRANSACTIONNUMBER)
  references SALETENDERHEADER (TRANSACTIONNUMBER) on delete cascade;
grant select on SALETENDERDETAIL to nst_nasaka_DB;

prompt Disabling triggers for GOODSCATEGORY...
alter table GOODSCATEGORY disable all triggers;
prompt Disabling triggers for GOODSSUBCATEGORY...
alter table GOODSSUBCATEGORY disable all triggers;
prompt Disabling triggers for FINISHEDGOODS...
alter table FINISHEDGOODS disable all triggers;
prompt Disabling triggers for GODOWN...
alter table GODOWN disable all triggers;
prompt Disabling triggers for PURCHASERCATEGORY...
alter table PURCHASERCATEGORY disable all triggers;
prompt Disabling triggers for GOODSPURCHASER...
alter table GOODSPURCHASER disable all triggers;
prompt Disabling triggers for GOODSSALEPERMISSION...
alter table GOODSSALEPERMISSION disable all triggers;
prompt Disabling triggers for GOODSSALETAXES...
alter table GOODSSALETAXES disable all triggers;
prompt Disabling triggers for SALECATEGORY...
alter table SALECATEGORY disable all triggers;
prompt Disabling triggers for GOODSTAXRATE...
alter table GOODSTAXRATE disable all triggers;
prompt Disabling triggers for PERIODRESETCATEGORY...
alter table PERIODRESETCATEGORY disable all triggers;
prompt Disabling triggers for SALENUMBERSERIES...
alter table SALENUMBERSERIES disable all triggers;
prompt Disabling triggers for SALEMEMOHEADER...
alter table SALEMEMOHEADER disable all triggers;
prompt Disabling triggers for SALEINVOICEHEADER...
alter table SALEINVOICEHEADER disable all triggers;
prompt Disabling triggers for SALEACCOUNTBRIDGE...
alter table SALEACCOUNTBRIDGE disable all triggers;
prompt Disabling triggers for SALECONTROLTABLE...
alter table SALECONTROLTABLE disable all triggers;
prompt Disabling triggers for SALEINVOICEDETAIL...
alter table SALEINVOICEDETAIL disable all triggers;
prompt Disabling triggers for SALEORDERHEADER...
alter table SALEORDERHEADER disable all triggers;
prompt Disabling triggers for SALETENDERHEADER...
alter table SALETENDERHEADER disable all triggers;
prompt Disabling triggers for SALEMEMODETAIL...
alter table SALEMEMODETAIL disable all triggers;
prompt Disabling triggers for SALEORDERDETAIL...
alter table SALEORDERDETAIL disable all triggers;
prompt Disabling triggers for SALEQUOTATIONHEADER...
alter table SALEQUOTATIONHEADER disable all triggers;
prompt Disabling triggers for SALEQUOTATIONDETAIL...
alter table SALEQUOTATIONDETAIL disable all triggers;
prompt Disabling triggers for SALETENDERDETAIL...
alter table SALETENDERDETAIL disable all triggers;
prompt Disabling foreign key constraints for GOODSSUBCATEGORY...
alter table GOODSSUBCATEGORY disable constraint FKCATCODE;
prompt Disabling foreign key constraints for FINISHEDGOODS...
alter table FINISHEDGOODS disable constraint FKGSUBCAT;
prompt Disabling foreign key constraints for GOODSPURCHASER...
alter table GOODSPURCHASER disable constraint FKGOODSPURCHASECD;
prompt Disabling foreign key constraints for GOODSTAXRATE...
alter table GOODSTAXRATE disable constraint FK112;
alter table GOODSTAXRATE disable constraint FKSALCATCD;
prompt Disabling foreign key constraints for SALENUMBERSERIES...
alter table SALENUMBERSERIES disable constraint FKGODCATCD;
alter table SALENUMBERSERIES disable constraint FKPERRESCAT;
prompt Disabling foreign key constraints for SALEMEMOHEADER...
alter table SALEMEMOHEADER disable constraint FKBRGODCAT;
alter table SALEMEMOHEADER disable constraint FKMENS;
alter table SALEMEMOHEADER disable constraint FKPURGODCAT;
alter table SALEMEMOHEADER disable constraint FKSHIPGODCAT;
prompt Disabling foreign key constraints for SALEINVOICEHEADER...
alter table SALEINVOICEHEADER disable constraint FKBRGC;
alter table SALEINVOICEHEADER disable constraint FKGOODSCAT;
alter table SALEINVOICEHEADER disable constraint FKNUMSERID;
alter table SALEINVOICEHEADER disable constraint FKSALECAT;
alter table SALEINVOICEHEADER disable constraint FKSALEMEMOTRNNUM;
prompt Disabling foreign key constraints for SALEACCOUNTBRIDGE...
alter table SALEACCOUNTBRIDGE disable constraint FKACTRNNO;
alter table SALEACCOUNTBRIDGE disable constraint FKSLTRNNO;
prompt Disabling foreign key constraints for SALEINVOICEDETAIL...
alter table SALEINVOICEDETAIL disable constraint FK76878;
alter table SALEINVOICEDETAIL disable constraint FKFINGODCD;
alter table SALEINVOICEDETAIL disable constraint FKGODNUM;
prompt Disabling foreign key constraints for SALEORDERHEADER...
alter table SALEORDERHEADER disable constraint FKPURCD1;
alter table SALEORDERHEADER disable constraint FKSALCAT;
prompt Disabling foreign key constraints for SALETENDERHEADER...
alter table SALETENDERHEADER disable constraint FKGODCATCD6;
alter table SALETENDERHEADER disable constraint FKPER;
alter table SALETENDERHEADER disable constraint FKSALECATCD2;
alter table SALETENDERHEADER disable constraint FKSALNUMSER3;
prompt Disabling foreign key constraints for SALEMEMODETAIL...
alter table SALEMEMODETAIL disable constraint FKFINGOODS;
alter table SALEMEMODETAIL disable constraint FKORTRAN;
alter table SALEMEMODETAIL disable constraint FKTENTRAN;
alter table SALEMEMODETAIL disable constraint FKTRNNO;
prompt Disabling foreign key constraints for SALEORDERDETAIL...
alter table SALEORDERDETAIL disable constraint FKFINGODCD1;
alter table SALEORDERDETAIL disable constraint FKSOH;
prompt Disabling foreign key constraints for SALEQUOTATIONHEADER...
alter table SALEQUOTATIONHEADER disable constraint FKPURGDCT;
alter table SALEQUOTATIONHEADER disable constraint FKQUOTNUM;
alter table SALEQUOTATIONHEADER disable constraint FKSALCATCD4;
prompt Disabling foreign key constraints for SALEQUOTATIONDETAIL...
alter table SALEQUOTATIONDETAIL disable constraint FKFINGODCD2;
alter table SALEQUOTATIONDETAIL disable constraint FKTRNHEA;
prompt Disabling foreign key constraints for SALETENDERDETAIL...
alter table SALETENDERDETAIL disable constraint FKFINGOD5;
alter table SALETENDERDETAIL disable constraint TRNTENDHE;
prompt Loading GOODSCATEGORY...
insert into GOODSCATEGORY (GOODSCATEGORYCODE, GOODSCATEGORYNAMEUNI, GOODSCATEGORYNAMEENG)
values (6, 'कंपोस्ट', 'Compost');
insert into GOODSCATEGORY (GOODSCATEGORYCODE, GOODSCATEGORYNAMEUNI, GOODSCATEGORYNAMEENG)
values (1, 'साखर', 'Sugar');
insert into GOODSCATEGORY (GOODSCATEGORYCODE, GOODSCATEGORYNAMEUNI, GOODSCATEGORYNAMEENG)
values (2, 'मळी', 'Molasses');
insert into GOODSCATEGORY (GOODSCATEGORYCODE, GOODSCATEGORYNAMEUNI, GOODSCATEGORYNAMEENG)
values (3, 'राख', 'Ash');
insert into GOODSCATEGORY (GOODSCATEGORYCODE, GOODSCATEGORYNAMEUNI, GOODSCATEGORYNAMEENG)
values (4, 'चिपाड', 'Bagasse');
insert into GOODSCATEGORY (GOODSCATEGORYCODE, GOODSCATEGORYNAMEUNI, GOODSCATEGORYNAMEENG)
values (5, 'प्रेसमड', 'Pressmud');
commit;
prompt 6 records loaded
prompt Loading GOODSSUBCATEGORY...
insert into GOODSSUBCATEGORY (SUBCATEGORYCODE, SUBCATEGORYNAMEUNI, SUBCATEGORYNAMEENG, CATEGORYCODE)
values (1, 'S30', 'S30', 1);
insert into GOODSSUBCATEGORY (SUBCATEGORYCODE, SUBCATEGORYNAMEUNI, SUBCATEGORYNAMEENG, CATEGORYCODE)
values (2, 'M30', 'M30', 1);
insert into GOODSSUBCATEGORY (SUBCATEGORYCODE, SUBCATEGORYNAMEUNI, SUBCATEGORYNAMEENG, CATEGORYCODE)
values (3, 'A Grade', 'A Grade', 2);
insert into GOODSSUBCATEGORY (SUBCATEGORYCODE, SUBCATEGORYNAMEUNI, SUBCATEGORYNAMEENG, CATEGORYCODE)
values (4, 'B Grade', 'B Grade', 2);
commit;
prompt 4 records loaded
prompt Loading FINISHEDGOODS...
insert into FINISHEDGOODS (FINISHEDGOODSCODE, FINISHEDGOODSNAMEUNI, FINISHEDGOODSNAMEENG, GOODSCATEGORYCODE, SUBCATEGORYCODE, CONVERSIONFACTOR, UNIT, HSNCODE)
values (1, 'पांढरी साखर s-३० ५० किग्रॅ pp', 'White Sugar-S30 50Kg PP', 1, 1, 50, 'Qtl.', null);
insert into FINISHEDGOODS (FINISHEDGOODSCODE, FINISHEDGOODSNAMEUNI, FINISHEDGOODSNAMEENG, GOODSCATEGORYCODE, SUBCATEGORYCODE, CONVERSIONFACTOR, UNIT, HSNCODE)
values (2, 'पांढरी साखर m-३० ५० किग्रॅ pp', 'White Sugar-M30 50Kg PP', 1, 1, 50, 'Qtl.', null);
insert into FINISHEDGOODS (FINISHEDGOODSCODE, FINISHEDGOODSNAMEUNI, FINISHEDGOODSNAMEENG, GOODSCATEGORYCODE, SUBCATEGORYCODE, CONVERSIONFACTOR, UNIT, HSNCODE)
values (3, 'मळी ए ग्रेड', 'Molasses A Grade', 2, 3, 1, 'M.T.', null);
insert into FINISHEDGOODS (FINISHEDGOODSCODE, FINISHEDGOODSNAMEUNI, FINISHEDGOODSNAMEENG, GOODSCATEGORYCODE, SUBCATEGORYCODE, CONVERSIONFACTOR, UNIT, HSNCODE)
values (4, 'मळी बी ग्रेड', 'Molasses B Grade', 2, 4, 1, 'M.T.', null);
insert into FINISHEDGOODS (FINISHEDGOODSCODE, FINISHEDGOODSNAMEUNI, FINISHEDGOODSNAMEENG, GOODSCATEGORYCODE, SUBCATEGORYCODE, CONVERSIONFACTOR, UNIT, HSNCODE)
values (5, 'राख', 'Ash', 3, null, 1, 'M.T.', null);
insert into FINISHEDGOODS (FINISHEDGOODSCODE, FINISHEDGOODSNAMEUNI, FINISHEDGOODSNAMEENG, GOODSCATEGORYCODE, SUBCATEGORYCODE, CONVERSIONFACTOR, UNIT, HSNCODE)
values (6, 'चिपाड', 'Bagasse', 4, null, 1, 'M.T.', null);
insert into FINISHEDGOODS (FINISHEDGOODSCODE, FINISHEDGOODSNAMEUNI, FINISHEDGOODSNAMEENG, GOODSCATEGORYCODE, SUBCATEGORYCODE, CONVERSIONFACTOR, UNIT, HSNCODE)
values (7, 'प्रेसमड', 'Pressmud', 5, null, 1, 'M.T.', null);
insert into FINISHEDGOODS (FINISHEDGOODSCODE, FINISHEDGOODSNAMEUNI, FINISHEDGOODSNAMEENG, GOODSCATEGORYCODE, SUBCATEGORYCODE, CONVERSIONFACTOR, UNIT, HSNCODE)
values (8, 'पांढरी साखर s-३० १०० किग्रॅ pp', 'White Sugar-S30 100Kg PP', 1, 1, 100, 'Qtl.', null);
insert into FINISHEDGOODS (FINISHEDGOODSCODE, FINISHEDGOODSNAMEUNI, FINISHEDGOODSNAMEENG, GOODSCATEGORYCODE, SUBCATEGORYCODE, CONVERSIONFACTOR, UNIT, HSNCODE)
values (9, 'पांढरी साखर m-३० १०० किग्रॅ pp', 'White Sugar-M30 100Kg PP', 1, 1, 100, 'Qtl.', null);
insert into FINISHEDGOODS (FINISHEDGOODSCODE, FINISHEDGOODSNAMEUNI, FINISHEDGOODSNAMEENG, GOODSCATEGORYCODE, SUBCATEGORYCODE, CONVERSIONFACTOR, UNIT, HSNCODE)
values (10, 'कंपोस्ट', 'Compost', 6, null, 1, 'M.T.', null);
commit;
prompt 10 records loaded
prompt Loading GODOWN...
insert into GODOWN (GODOWNNUMBER, GODOWNNAMEENG, GODOWNNAMEUNI, GODOWNCATEGORYCODE, GODOWNCAPACITY)
values (1, 'Godown No 1', 'Godown No 1', 1, 100000);
insert into GODOWN (GODOWNNUMBER, GODOWNNAMEENG, GODOWNNAMEUNI, GODOWNCATEGORYCODE, GODOWNCAPACITY)
values (2, 'Godown No 2', 'Godown No 2', 1, 100000);
insert into GODOWN (GODOWNNUMBER, GODOWNNAMEENG, GODOWNNAMEUNI, GODOWNCATEGORYCODE, GODOWNCAPACITY)
values (3, 'Godown No 3', 'Godown No 3', 1, 100000);
insert into GODOWN (GODOWNNUMBER, GODOWNNAMEENG, GODOWNNAMEUNI, GODOWNCATEGORYCODE, GODOWNCAPACITY)
values (4, 'Godown No 4', 'Godown No 4', 1, 100000);
commit;
prompt 4 records loaded
prompt Loading PURCHASERCATEGORY...
insert into PURCHASERCATEGORY (PURCHASERCATEGORYCODE, PURCHASERCATEGORYNAMEUNI, PURCHASERCATEGORYNAMEENG)
values (1, 'दलाल', 'Broker');
insert into PURCHASERCATEGORY (PURCHASERCATEGORYCODE, PURCHASERCATEGORYNAMEUNI, PURCHASERCATEGORYNAMEENG)
values (2, 'खरेदीदार', 'Purchaser');
commit;
prompt 2 records loaded
prompt Loading GOODSPURCHASER...
insert into GOODSPURCHASER (PURCHASERCODE, PURCHASERNAMEUNI, PURCHASERNAMEENG, GOODSCATEGORYCODE, PANNUMBER, GSTINNUMBER, CONTACTNUMBER, CONTACTPERSON, ADDRESS, EMAILID, STATECODE, PURCHASERCATEGORYCODE)
values (1, 'भोसले  सन्स', 'Bhosale sons', 1, null, '27AFSPN5611H1Z5', null, null, 'hadapsar', null, 27, 1);
insert into GOODSPURCHASER (PURCHASERCODE, PURCHASERNAMEUNI, PURCHASERNAMEENG, GOODSCATEGORYCODE, PANNUMBER, GSTINNUMBER, CONTACTNUMBER, CONTACTPERSON, ADDRESS, EMAILID, STATECODE, PURCHASERCATEGORYCODE)
values (2, 'रामलाल सन्स', 'Ramlal sons', 1, null, '27AFSPN5611H1Z5', null, null, 'Pune', null, 27, 1);
insert into GOODSPURCHASER (PURCHASERCODE, PURCHASERNAMEUNI, PURCHASERNAMEENG, GOODSCATEGORYCODE, PANNUMBER, GSTINNUMBER, CONTACTNUMBER, CONTACTPERSON, ADDRESS, EMAILID, STATECODE, PURCHASERCATEGORYCODE)
values (3, 'शामराव पवार सन्स', 'Shamrao Pawar sons', 1, null, '27AFSPN5611H1Z5', null, null, 'SNo-19, Gondhalenagar', null, 27, 2);
insert into GOODSPURCHASER (PURCHASERCODE, PURCHASERNAMEUNI, PURCHASERNAMEENG, GOODSCATEGORYCODE, PANNUMBER, GSTINNUMBER, CONTACTNUMBER, CONTACTPERSON, ADDRESS, EMAILID, STATECODE, PURCHASERCATEGORYCODE)
values (4, 'गजानन सेल्स', 'Gajanan Sales', 1, null, '27AFSPN5611H1Z5', null, null, 'vapi', null, 24, 2);
commit;
prompt 4 records loaded
prompt Loading GOODSSALEPERMISSION...
insert into GOODSSALEPERMISSION (TRANSACTIONNUMBER, PERMISSIONNUMBER, PERMISSIONDATE, PERMISSIONQUNTITY, LIFTINGFROMDATE, LIFTINGTODATE, EXTENDEDTRANSACTIONNUMBER, REMARK, GOODSCATEGORYCODE)
values (1, '12457854', to_date('03-03-2019', 'dd-mm-yyyy'), 100000, to_date('01-03-2019', 'dd-mm-yyyy'), to_date('31-03-2019', 'dd-mm-yyyy'), null, null, 1);
commit;
prompt 1 records loaded
prompt Loading GOODSSALETAXES...
insert into GOODSSALETAXES (TAXCODE, TAXNAME)
values (1, 'GST');
insert into GOODSSALETAXES (TAXCODE, TAXNAME)
values (2, 'VAT');
commit;
prompt 2 records loaded
prompt Loading SALECATEGORY...
insert into SALECATEGORY (SALECATEGORYCODE, SALECATEGORYNAMEUNI, SALECATEGORYNAMEENG, SALECATEGORYSHORTNAME, GOODSCATEGORYCODE, TENDERSALENUMBERSERIESID, QUOTATIONSALENUMBERSERIESID, SALEORDERSALENUMBERSERIESID, SALEINVOICENUMBERSERIESID, SALEMEMONUMBERSERIESID)
values (1, 'फ्री', 'Free', '', 1, 1, 2, 3, 6, 4);
insert into SALECATEGORY (SALECATEGORYCODE, SALECATEGORYNAMEUNI, SALECATEGORYNAMEENG, SALECATEGORYSHORTNAME, GOODSCATEGORYCODE, TENDERSALENUMBERSERIESID, QUOTATIONSALENUMBERSERIESID, SALEORDERSALENUMBERSERIESID, SALEINVOICENUMBERSERIESID, SALEMEMONUMBERSERIESID)
values (2, 'लेव्ही', 'Levy', '', 1, 1, 2, 3, 6, 4);
insert into SALECATEGORY (SALECATEGORYCODE, SALECATEGORYNAMEUNI, SALECATEGORYNAMEENG, SALECATEGORYSHORTNAME, GOODSCATEGORYCODE, TENDERSALENUMBERSERIESID, QUOTATIONSALENUMBERSERIESID, SALEORDERSALENUMBERSERIESID, SALEINVOICENUMBERSERIESID, SALEMEMONUMBERSERIESID)
values (3, 'निर्यात', 'Export', '', 1, 1, 2, 3, 7, 5);
insert into SALECATEGORY (SALECATEGORYCODE, SALECATEGORYNAMEUNI, SALECATEGORYNAMEENG, SALECATEGORYSHORTNAME, GOODSCATEGORYCODE, TENDERSALENUMBERSERIESID, QUOTATIONSALENUMBERSERIESID, SALEORDERSALENUMBERSERIESID, SALEINVOICENUMBERSERIESID, SALEMEMONUMBERSERIESID)
values (4, 'देशांतर्गत', 'Domestic', '', 2, null, null, null, null, null);
insert into SALECATEGORY (SALECATEGORYCODE, SALECATEGORYNAMEUNI, SALECATEGORYNAMEENG, SALECATEGORYSHORTNAME, GOODSCATEGORYCODE, TENDERSALENUMBERSERIESID, QUOTATIONSALENUMBERSERIESID, SALEORDERSALENUMBERSERIESID, SALEINVOICENUMBERSERIESID, SALEMEMONUMBERSERIESID)
values (5, 'सर्वसाधारण', 'General', '', 3, null, null, null, null, null);
insert into SALECATEGORY (SALECATEGORYCODE, SALECATEGORYNAMEUNI, SALECATEGORYNAMEENG, SALECATEGORYSHORTNAME, GOODSCATEGORYCODE, TENDERSALENUMBERSERIESID, QUOTATIONSALENUMBERSERIESID, SALEORDERSALENUMBERSERIESID, SALEINVOICENUMBERSERIESID, SALEMEMONUMBERSERIESID)
values (6, 'सर्वसाधारण', 'General', '', 4, null, null, null, null, null);
insert into SALECATEGORY (SALECATEGORYCODE, SALECATEGORYNAMEUNI, SALECATEGORYNAMEENG, SALECATEGORYSHORTNAME, GOODSCATEGORYCODE, TENDERSALENUMBERSERIESID, QUOTATIONSALENUMBERSERIESID, SALEORDERSALENUMBERSERIESID, SALEINVOICENUMBERSERIESID, SALEMEMONUMBERSERIESID)
values (7, 'सर्वसाधारण', 'General', '', 5, null, null, null, null, null);
commit;
prompt 7 records loaded
prompt Loading GOODSTAXRATE...
insert into GOODSTAXRATE (TRANSACTIONNUMBER, FINISHEDGOODSCODE, TAXCODE, FROMDATE, TODATE, TAXPERCENT, SALECATEGORYCODE)
values (1, 1, 1, to_date('01-01-2019', 'dd-mm-yyyy'), to_date('31-12-2020', 'dd-mm-yyyy'), 5, 1);
insert into GOODSTAXRATE (TRANSACTIONNUMBER, FINISHEDGOODSCODE, TAXCODE, FROMDATE, TODATE, TAXPERCENT, SALECATEGORYCODE)
values (2, 3, 1, to_date('01-01-2019', 'dd-mm-yyyy'), to_date('31-12-2020', 'dd-mm-yyyy'), 5, 1);
insert into GOODSTAXRATE (TRANSACTIONNUMBER, FINISHEDGOODSCODE, TAXCODE, FROMDATE, TODATE, TAXPERCENT, SALECATEGORYCODE)
values (3, 2, 1, to_date('01-01-2019', 'dd-mm-yyyy'), to_date('31-12-2020', 'dd-mm-yyyy'), 5, 1);
insert into GOODSTAXRATE (TRANSACTIONNUMBER, FINISHEDGOODSCODE, TAXCODE, FROMDATE, TODATE, TAXPERCENT, SALECATEGORYCODE)
values (4, 4, 1, to_date('01-01-2019', 'dd-mm-yyyy'), to_date('31-12-2020', 'dd-mm-yyyy'), 5, 1);
insert into GOODSTAXRATE (TRANSACTIONNUMBER, FINISHEDGOODSCODE, TAXCODE, FROMDATE, TODATE, TAXPERCENT, SALECATEGORYCODE)
values (5, 5, 1, to_date('01-01-2019', 'dd-mm-yyyy'), to_date('31-12-2020', 'dd-mm-yyyy'), 5, 1);
insert into GOODSTAXRATE (TRANSACTIONNUMBER, FINISHEDGOODSCODE, TAXCODE, FROMDATE, TODATE, TAXPERCENT, SALECATEGORYCODE)
values (6, 6, 1, to_date('01-01-2019', 'dd-mm-yyyy'), to_date('31-12-2020', 'dd-mm-yyyy'), 5, 1);
insert into GOODSTAXRATE (TRANSACTIONNUMBER, FINISHEDGOODSCODE, TAXCODE, FROMDATE, TODATE, TAXPERCENT, SALECATEGORYCODE)
values (7, 7, 1, to_date('01-01-2019', 'dd-mm-yyyy'), to_date('31-12-2020', 'dd-mm-yyyy'), 5, 1);
insert into GOODSTAXRATE (TRANSACTIONNUMBER, FINISHEDGOODSCODE, TAXCODE, FROMDATE, TODATE, TAXPERCENT, SALECATEGORYCODE)
values (8, 8, 1, to_date('01-01-2019', 'dd-mm-yyyy'), to_date('31-12-2020', 'dd-mm-yyyy'), 5, 1);
insert into GOODSTAXRATE (TRANSACTIONNUMBER, FINISHEDGOODSCODE, TAXCODE, FROMDATE, TODATE, TAXPERCENT, SALECATEGORYCODE)
values (9, 9, 1, to_date('01-01-2019', 'dd-mm-yyyy'), to_date('31-12-2020', 'dd-mm-yyyy'), 5, 1);
commit;
prompt 9 records loaded
prompt Loading PERIODRESETCATEGORY...
insert into PERIODRESETCATEGORY (PERIODRESETCATEGORYCODE, PERIODRESETCATEGORYNAME)
values (1, 'Daily');
insert into PERIODRESETCATEGORY (PERIODRESETCATEGORYCODE, PERIODRESETCATEGORYNAME)
values (2, 'Monthly');
insert into PERIODRESETCATEGORY (PERIODRESETCATEGORYCODE, PERIODRESETCATEGORYNAME)
values (3, 'Quarterly');
insert into PERIODRESETCATEGORY (PERIODRESETCATEGORYCODE, PERIODRESETCATEGORYNAME)
values (4, 'Yearly');
commit;
prompt 4 records loaded
prompt Loading SALENUMBERSERIES...
insert into SALENUMBERSERIES (SALENUMBERSERIESID, SALENUMBERSERIESNAMEENG, SALENUMBERSERIESNAMEUNI, SALENUMBERSTARTINGFROM, PERIODRESETCATEGORYCODE, SALENUMBERPREFIX, GOODSCATEGORYCODE, TRANSACTIONCATEGORYCODE)
values (2, 'Sugar Sale Quotation', 'साखर विक्री कोटेशन ', 1, 4, 'SQ', 1, 2);
insert into SALENUMBERSERIES (SALENUMBERSERIESID, SALENUMBERSERIESNAMEENG, SALENUMBERSERIESNAMEUNI, SALENUMBERSTARTINGFROM, PERIODRESETCATEGORYCODE, SALENUMBERPREFIX, GOODSCATEGORYCODE, TRANSACTIONCATEGORYCODE)
values (3, 'Sugar Sale Order', 'साखर विक्री ऑर्डर', 1, 4, 'SO', 1, 3);
insert into SALENUMBERSERIES (SALENUMBERSERIESID, SALENUMBERSERIESNAMEENG, SALENUMBERSERIESNAMEUNI, SALENUMBERSTARTINGFROM, PERIODRESETCATEGORYCODE, SALENUMBERPREFIX, GOODSCATEGORYCODE, TRANSACTIONCATEGORYCODE)
values (4, 'Free Sugar Sale Memo', 'खुली साखर विक्री मेमो ', 1, 4, 'MF', 1, 4);
insert into SALENUMBERSERIES (SALENUMBERSERIESID, SALENUMBERSERIESNAMEENG, SALENUMBERSERIESNAMEUNI, SALENUMBERSTARTINGFROM, PERIODRESETCATEGORYCODE, SALENUMBERPREFIX, GOODSCATEGORYCODE, TRANSACTIONCATEGORYCODE)
values (5, 'Export Sugar Sale Memo', 'निर्यात साखर विक्री मेमो ', 1, 4, 'ME', 1, 4);
insert into SALENUMBERSERIES (SALENUMBERSERIESID, SALENUMBERSERIESNAMEENG, SALENUMBERSERIESNAMEUNI, SALENUMBERSTARTINGFROM, PERIODRESETCATEGORYCODE, SALENUMBERPREFIX, GOODSCATEGORYCODE, TRANSACTIONCATEGORYCODE)
values (1, 'Sugar Sale Tender', 'साखर विक्री टेंडर ', 1, 4, 'ST', 1, 1);
insert into SALENUMBERSERIES (SALENUMBERSERIESID, SALENUMBERSERIESNAMEENG, SALENUMBERSERIESNAMEUNI, SALENUMBERSTARTINGFROM, PERIODRESETCATEGORYCODE, SALENUMBERPREFIX, GOODSCATEGORYCODE, TRANSACTIONCATEGORYCODE)
values (6, 'Free Sugar Sale Memo', 'खुली साखर विक्री मेमो ', 1, 4, 'SF', 1, 4);
insert into SALENUMBERSERIES (SALENUMBERSERIESID, SALENUMBERSERIESNAMEENG, SALENUMBERSERIESNAMEUNI, SALENUMBERSTARTINGFROM, PERIODRESETCATEGORYCODE, SALENUMBERPREFIX, GOODSCATEGORYCODE, TRANSACTIONCATEGORYCODE)
values (7, 'Export Sugar Sale Memo', 'निर्यात साखर विक्री मेमो ', 1, 4, 'SE', 1, 4);
commit;
prompt 7 records loaded
prompt Loading SALEMEMOHEADER...
insert into SALEMEMOHEADER (TRANSACTIONNUMBER, GOODSCATEGORYCODE, SALECATEGORYCODE, YEARPERIODCODE, SALEMEMONUMBERSERIESID, SALEMEMONUMBERBASEVALUE, MEMONUMBER, MEMONUMBERPRESUF, MEMODATE, BROKERCODE, PURCHASERCODE, SHIPPINGPARTYCODE, PREPARATIONTIME, REMOVALTIME, VEHICLENUMBER, DRIVERNAME, DRIVERLICENSENO, TAXABLEAMOUNT, CGSTAMOUNT, SGSTAMOUNT, IGSTAMOUNT, UGSTAMOUNT, VATAMOUNT, TOTALTAXAMOUNT, GROSSAMOUNT)
values (2, 1, 1, 20182019, 4, 'Y20182019', 2, 'MF00002', to_date('22-03-2019', 'dd-mm-yyyy'), 1, 1, 1, to_date('22-03-2019 17:29:00', 'dd-mm-yyyy hh24:mi:ss'), to_date('22-03-2019 17:59:00', 'dd-mm-yyyy hh24:mi:ss'), null, 'somanath rama ranga', null, 195000, 4875, 4875, null, 0, null, 9750, 204750);
insert into SALEMEMOHEADER (TRANSACTIONNUMBER, GOODSCATEGORYCODE, SALECATEGORYCODE, YEARPERIODCODE, SALEMEMONUMBERSERIESID, SALEMEMONUMBERBASEVALUE, MEMONUMBER, MEMONUMBERPRESUF, MEMODATE, BROKERCODE, PURCHASERCODE, SHIPPINGPARTYCODE, PREPARATIONTIME, REMOVALTIME, VEHICLENUMBER, DRIVERNAME, DRIVERLICENSENO, TAXABLEAMOUNT, CGSTAMOUNT, SGSTAMOUNT, IGSTAMOUNT, UGSTAMOUNT, VATAMOUNT, TOTALTAXAMOUNT, GROSSAMOUNT)
values (1, 1, 1, 20182019, 4, 'Y20182019', 1, 'MF00001', to_date('22-03-2019', 'dd-mm-yyyy'), 1, 1, 1, to_date('22-03-2019 17:12:00', 'dd-mm-yyyy hh24:mi:ss'), to_date('22-03-2019 17:42:00', 'dd-mm-yyyy hh24:mi:ss'), null, 'somanath rama ranga', null, 483600, 12090, 12090, null, 0, null, 24180, 507780);
insert into SALEMEMOHEADER (TRANSACTIONNUMBER, GOODSCATEGORYCODE, SALECATEGORYCODE, YEARPERIODCODE, SALEMEMONUMBERSERIESID, SALEMEMONUMBERBASEVALUE, MEMONUMBER, MEMONUMBERPRESUF, MEMODATE, BROKERCODE, PURCHASERCODE, SHIPPINGPARTYCODE, PREPARATIONTIME, REMOVALTIME, VEHICLENUMBER, DRIVERNAME, DRIVERLICENSENO, TAXABLEAMOUNT, CGSTAMOUNT, SGSTAMOUNT, IGSTAMOUNT, UGSTAMOUNT, VATAMOUNT, TOTALTAXAMOUNT, GROSSAMOUNT)
values (3, 1, 1, 20182019, 4, 'Y20182019', 3, 'MF00003', to_date('22-03-2019', 'dd-mm-yyyy'), 2, 2, 2, to_date('22-03-2019 17:30:00', 'dd-mm-yyyy hh24:mi:ss'), to_date('22-03-2019 18:00:00', 'dd-mm-yyyy hh24:mi:ss'), null, 'somanath rama ranga', null, 2160000, 54000, 54000, null, 0, null, 108000, 2268000);
insert into SALEMEMOHEADER (TRANSACTIONNUMBER, GOODSCATEGORYCODE, SALECATEGORYCODE, YEARPERIODCODE, SALEMEMONUMBERSERIESID, SALEMEMONUMBERBASEVALUE, MEMONUMBER, MEMONUMBERPRESUF, MEMODATE, BROKERCODE, PURCHASERCODE, SHIPPINGPARTYCODE, PREPARATIONTIME, REMOVALTIME, VEHICLENUMBER, DRIVERNAME, DRIVERLICENSENO, TAXABLEAMOUNT, CGSTAMOUNT, SGSTAMOUNT, IGSTAMOUNT, UGSTAMOUNT, VATAMOUNT, TOTALTAXAMOUNT, GROSSAMOUNT)
values (5, 1, 1, 20182019, 4, 'Y20182019', 5, 'MF00005', to_date('23-03-2019', 'dd-mm-yyyy'), 2, 2, 2, to_date('23-03-2019 17:02:00', 'dd-mm-yyyy hh24:mi:ss'), to_date('23-03-2019 17:32:00', 'dd-mm-yyyy hh24:mi:ss'), 'mh433', 'somanath rama ranga', null, 3420000, 85500, 85500, null, 0, null, 171000, 3591000);
insert into SALEMEMOHEADER (TRANSACTIONNUMBER, GOODSCATEGORYCODE, SALECATEGORYCODE, YEARPERIODCODE, SALEMEMONUMBERSERIESID, SALEMEMONUMBERBASEVALUE, MEMONUMBER, MEMONUMBERPRESUF, MEMODATE, BROKERCODE, PURCHASERCODE, SHIPPINGPARTYCODE, PREPARATIONTIME, REMOVALTIME, VEHICLENUMBER, DRIVERNAME, DRIVERLICENSENO, TAXABLEAMOUNT, CGSTAMOUNT, SGSTAMOUNT, IGSTAMOUNT, UGSTAMOUNT, VATAMOUNT, TOTALTAXAMOUNT, GROSSAMOUNT)
values (6, 1, 1, 20182019, 4, 'Y20182019', 6, 'MF00006', to_date('23-03-2019', 'dd-mm-yyyy'), 2, 2, 2, to_date('23-03-2019 17:10:00', 'dd-mm-yyyy hh24:mi:ss'), to_date('23-03-2019 17:40:00', 'dd-mm-yyyy hh24:mi:ss'), 'mh-23', 'somanath rama ranga', null, null, null, null, null, null, null, null, null);
insert into SALEMEMOHEADER (TRANSACTIONNUMBER, GOODSCATEGORYCODE, SALECATEGORYCODE, YEARPERIODCODE, SALEMEMONUMBERSERIESID, SALEMEMONUMBERBASEVALUE, MEMONUMBER, MEMONUMBERPRESUF, MEMODATE, BROKERCODE, PURCHASERCODE, SHIPPINGPARTYCODE, PREPARATIONTIME, REMOVALTIME, VEHICLENUMBER, DRIVERNAME, DRIVERLICENSENO, TAXABLEAMOUNT, CGSTAMOUNT, SGSTAMOUNT, IGSTAMOUNT, UGSTAMOUNT, VATAMOUNT, TOTALTAXAMOUNT, GROSSAMOUNT)
values (4, 1, 1, 20182019, 4, 'Y20182019', 4, 'MF00004', to_date('22-03-2019', 'dd-mm-yyyy'), 2, 2, 2, to_date('22-03-2019 17:31:00', 'dd-mm-yyyy hh24:mi:ss'), to_date('22-03-2019 18:01:00', 'dd-mm-yyyy hh24:mi:ss'), null, 'somanath rama ranga', null, 180000, 4500, 4500, null, 0, null, 9000, 189000);
insert into SALEMEMOHEADER (TRANSACTIONNUMBER, GOODSCATEGORYCODE, SALECATEGORYCODE, YEARPERIODCODE, SALEMEMONUMBERSERIESID, SALEMEMONUMBERBASEVALUE, MEMONUMBER, MEMONUMBERPRESUF, MEMODATE, BROKERCODE, PURCHASERCODE, SHIPPINGPARTYCODE, PREPARATIONTIME, REMOVALTIME, VEHICLENUMBER, DRIVERNAME, DRIVERLICENSENO, TAXABLEAMOUNT, CGSTAMOUNT, SGSTAMOUNT, IGSTAMOUNT, UGSTAMOUNT, VATAMOUNT, TOTALTAXAMOUNT, GROSSAMOUNT)
values (7, 1, 1, 20182019, 4, 'Y20182019', 7, 'MF00007', to_date('23-03-2019', 'dd-mm-yyyy'), 1, 1, 1, to_date('23-03-2019 17:11:00', 'dd-mm-yyyy hh24:mi:ss'), to_date('23-03-2019 17:41:00', 'dd-mm-yyyy hh24:mi:ss'), 'mfdg678', 'somanath rama ranga', null, 780000, 19500, 19500, null, 0, null, 39000, 819000);
commit;
prompt 7 records loaded
prompt Loading SALEINVOICEHEADER...
insert into SALEINVOICEHEADER (TRANSACTIONNUMBER, SALEMEMOTRANSACTIONNUMBER, GOODSCATEGORYCODE, SALECATEGORYCODE, YEARPERIODCODE, SALEINVOICENUMBERSERIESID, SALEINVOICENUMBERBASEVALUE, INVOICENUMBER, INVOICENUMBERPRESUF, INVOICEDATE, BROKERCODE, PURCHASERCODE, SHIPPINGPARTYCODE, PREPARATIONTIME, REMOVALTIME, VEHICLENUMBER, DRIVERNAME, DRIVERLICENSENO, TAXABLEAMOUNT, CGSTAMOUNT, SGSTAMOUNT, IGSTAMOUNT, UGSTAMOUNT, VATAMOUNT, TOTALTAXAMOUNT, GROSSAMOUNT, BOOKINGSTATION, RECEIVINGSTATION)
values (8, 3, 1, 1, 20182019, 6, null, null, null, to_date('23-03-2019', 'dd-mm-yyyy'), 2, 2, 2, to_date('23-03-2019 16:15:00', 'dd-mm-yyyy hh24:mi:ss'), to_date('23-03-2019 16:45:00', 'dd-mm-yyyy hh24:mi:ss'), 'kk45646', null, null, 0, 18000, 18000, null, 0, null, 36000, 756000, 'Manmad', 'Pune');
insert into SALEINVOICEHEADER (TRANSACTIONNUMBER, SALEMEMOTRANSACTIONNUMBER, GOODSCATEGORYCODE, SALECATEGORYCODE, YEARPERIODCODE, SALEINVOICENUMBERSERIESID, SALEINVOICENUMBERBASEVALUE, INVOICENUMBER, INVOICENUMBERPRESUF, INVOICEDATE, BROKERCODE, PURCHASERCODE, SHIPPINGPARTYCODE, PREPARATIONTIME, REMOVALTIME, VEHICLENUMBER, DRIVERNAME, DRIVERLICENSENO, TAXABLEAMOUNT, CGSTAMOUNT, SGSTAMOUNT, IGSTAMOUNT, UGSTAMOUNT, VATAMOUNT, TOTALTAXAMOUNT, GROSSAMOUNT, BOOKINGSTATION, RECEIVINGSTATION)
values (12, 4, 1, 1, 20182019, 6, null, null, null, to_date('23-03-2019', 'dd-mm-yyyy'), 2, 2, 2, to_date('23-03-2019 16:24:00', 'dd-mm-yyyy hh24:mi:ss'), to_date('23-03-2019 16:54:00', 'dd-mm-yyyy hh24:mi:ss'), 'm787', null, null, 0, 4500, 4500, null, 0, null, 9000, 189000, 'Manmad', 'Pune');
insert into SALEINVOICEHEADER (TRANSACTIONNUMBER, SALEMEMOTRANSACTIONNUMBER, GOODSCATEGORYCODE, SALECATEGORYCODE, YEARPERIODCODE, SALEINVOICENUMBERSERIESID, SALEINVOICENUMBERBASEVALUE, INVOICENUMBER, INVOICENUMBERPRESUF, INVOICEDATE, BROKERCODE, PURCHASERCODE, SHIPPINGPARTYCODE, PREPARATIONTIME, REMOVALTIME, VEHICLENUMBER, DRIVERNAME, DRIVERLICENSENO, TAXABLEAMOUNT, CGSTAMOUNT, SGSTAMOUNT, IGSTAMOUNT, UGSTAMOUNT, VATAMOUNT, TOTALTAXAMOUNT, GROSSAMOUNT, BOOKINGSTATION, RECEIVINGSTATION)
values (6, 2, 1, 1, 20182019, 6, 'Y20182019', 1, 'SF00001', to_date('23-03-2019', 'dd-mm-yyyy'), 1, 1, 1, to_date('23-03-2019 16:06:00', 'dd-mm-yyyy hh24:mi:ss'), to_date('23-03-2019 16:36:00', 'dd-mm-yyyy hh24:mi:ss'), 'Mh12-6685', null, null, 0, 4875, 4875, null, 0, null, 9750, 204750, 'Manmad', 'Pune');
insert into SALEINVOICEHEADER (TRANSACTIONNUMBER, SALEMEMOTRANSACTIONNUMBER, GOODSCATEGORYCODE, SALECATEGORYCODE, YEARPERIODCODE, SALEINVOICENUMBERSERIESID, SALEINVOICENUMBERBASEVALUE, INVOICENUMBER, INVOICENUMBERPRESUF, INVOICEDATE, BROKERCODE, PURCHASERCODE, SHIPPINGPARTYCODE, PREPARATIONTIME, REMOVALTIME, VEHICLENUMBER, DRIVERNAME, DRIVERLICENSENO, TAXABLEAMOUNT, CGSTAMOUNT, SGSTAMOUNT, IGSTAMOUNT, UGSTAMOUNT, VATAMOUNT, TOTALTAXAMOUNT, GROSSAMOUNT, BOOKINGSTATION, RECEIVINGSTATION)
values (9, 3, 1, 1, 20182019, 6, null, null, null, to_date('23-03-2019', 'dd-mm-yyyy'), 2, 2, 2, to_date('23-03-2019 16:19:00', 'dd-mm-yyyy hh24:mi:ss'), to_date('23-03-2019 16:49:00', 'dd-mm-yyyy hh24:mi:ss'), 'mgk343', null, null, 0, 4500, 4500, null, 0, null, 9000, 189000, 'Manmad', 'Pune');
insert into SALEINVOICEHEADER (TRANSACTIONNUMBER, SALEMEMOTRANSACTIONNUMBER, GOODSCATEGORYCODE, SALECATEGORYCODE, YEARPERIODCODE, SALEINVOICENUMBERSERIESID, SALEINVOICENUMBERBASEVALUE, INVOICENUMBER, INVOICENUMBERPRESUF, INVOICEDATE, BROKERCODE, PURCHASERCODE, SHIPPINGPARTYCODE, PREPARATIONTIME, REMOVALTIME, VEHICLENUMBER, DRIVERNAME, DRIVERLICENSENO, TAXABLEAMOUNT, CGSTAMOUNT, SGSTAMOUNT, IGSTAMOUNT, UGSTAMOUNT, VATAMOUNT, TOTALTAXAMOUNT, GROSSAMOUNT, BOOKINGSTATION, RECEIVINGSTATION)
values (10, 3, 1, 1, 20182019, 6, null, null, null, to_date('23-03-2019', 'dd-mm-yyyy'), 2, 2, 2, to_date('23-03-2019 16:21:00', 'dd-mm-yyyy hh24:mi:ss'), to_date('23-03-2019 16:51:00', 'dd-mm-yyyy hh24:mi:ss'), 'hgj45', null, null, 0, 13500, 13500, null, 0, null, 27000, 567000, 'Manmad', 'Pune');
insert into SALEINVOICEHEADER (TRANSACTIONNUMBER, SALEMEMOTRANSACTIONNUMBER, GOODSCATEGORYCODE, SALECATEGORYCODE, YEARPERIODCODE, SALEINVOICENUMBERSERIESID, SALEINVOICENUMBERBASEVALUE, INVOICENUMBER, INVOICENUMBERPRESUF, INVOICEDATE, BROKERCODE, PURCHASERCODE, SHIPPINGPARTYCODE, PREPARATIONTIME, REMOVALTIME, VEHICLENUMBER, DRIVERNAME, DRIVERLICENSENO, TAXABLEAMOUNT, CGSTAMOUNT, SGSTAMOUNT, IGSTAMOUNT, UGSTAMOUNT, VATAMOUNT, TOTALTAXAMOUNT, GROSSAMOUNT, BOOKINGSTATION, RECEIVINGSTATION)
values (11, 3, 1, 1, 20182019, 6, null, null, null, to_date('23-03-2019', 'dd-mm-yyyy'), 2, 2, 2, to_date('23-03-2019 16:23:00', 'dd-mm-yyyy hh24:mi:ss'), to_date('23-03-2019 16:53:00', 'dd-mm-yyyy hh24:mi:ss'), 'mh34', null, null, 0, 9000, 9000, null, 0, null, 18000, 378000, 'Manmad', 'Pune');
commit;
prompt 6 records loaded
prompt Loading SALEACCOUNTBRIDGE...
insert into SALEACCOUNTBRIDGE (GOODSCATEGORYCODE, SALETRANSACTIONNUMBER, ACCOUNTTRANSACTIONNUMBER)
values (1, 6, 20182019163283);
commit;
prompt 1 records loaded
prompt Loading SALECONTROLTABLE...
insert into SALECONTROLTABLE (GOODSCATEGORYCODE, DEBTORSACCOUNTCODE, POSTINGCATEGORY, SALEACCOUNTCODE, CGSTACCOUNTCODE, SGSTACCOUNTCODE, IGSTACCOUNTCODE, UGSTACCOUNTCODE, VATACCOUNTCODE, GSTEXPENSES)
values (1, 1214002, 1, 1405001, 1106032, 1106033, 1106034, 1106034, 1106034, 1405009);
insert into SALECONTROLTABLE (GOODSCATEGORYCODE, DEBTORSACCOUNTCODE, POSTINGCATEGORY, SALEACCOUNTCODE, CGSTACCOUNTCODE, SGSTACCOUNTCODE, IGSTACCOUNTCODE, UGSTACCOUNTCODE, VATACCOUNTCODE, GSTEXPENSES)
values (1, 1214002, 2, 1405001, 1106032, 1106033, 1106034, 1106034, 1106034, 1405009);
commit;
prompt 2 records loaded
prompt Loading SALEINVOICEDETAIL...
insert into SALEINVOICEDETAIL (TRANSACTIONNUMBER, SERIALNUMBER, FINISHEDGOODSCODE, PRODUCTIONYEARCODE, SALEQUANTITY, SALERATE, AMOUNT, CGSTRATE, SGSTRATE, IGSTRATE, UGSTRATE, VATRATE, CGSTAMOUNT, SGSTAMOUNT, IGSTAMOUNT, UGSTAMOUNT, VATAMOUNT, TOTALTAXAMOUNT, GROSSAMOUNT, GODOWNNUMBER, LOTNUMBER)
values (6, 2, 9, 20172018, 45, 3900, 175500, 2.5, 2.5, 0, 0, null, 4387.5, 4387.5, 0, 0, 0, 8775, 184275, 2, 1);
insert into SALEINVOICEDETAIL (TRANSACTIONNUMBER, SERIALNUMBER, FINISHEDGOODSCODE, PRODUCTIONYEARCODE, SALEQUANTITY, SALERATE, AMOUNT, CGSTRATE, SGSTRATE, IGSTRATE, UGSTRATE, VATRATE, CGSTAMOUNT, SGSTAMOUNT, IGSTAMOUNT, UGSTAMOUNT, VATAMOUNT, TOTALTAXAMOUNT, GROSSAMOUNT, GODOWNNUMBER, LOTNUMBER)
values (8, 4, 9, 20172018, 100, 3600, 360000, 2.5, 2.5, 0, 0, null, 9000, 9000, 0, 0, null, 18000, 378000, 1, 1);
insert into SALEINVOICEDETAIL (TRANSACTIONNUMBER, SERIALNUMBER, FINISHEDGOODSCODE, PRODUCTIONYEARCODE, SALEQUANTITY, SALERATE, AMOUNT, CGSTRATE, SGSTRATE, IGSTRATE, UGSTRATE, VATRATE, CGSTAMOUNT, SGSTAMOUNT, IGSTAMOUNT, UGSTAMOUNT, VATAMOUNT, TOTALTAXAMOUNT, GROSSAMOUNT, GODOWNNUMBER, LOTNUMBER)
values (12, 10, 9, 20172018, 50, 3600, 180000, 2.5, 2.5, 0, 0, null, 4500, 4500, 0, 0, null, 9000, 189000, 2, 1);
insert into SALEINVOICEDETAIL (TRANSACTIONNUMBER, SERIALNUMBER, FINISHEDGOODSCODE, PRODUCTIONYEARCODE, SALEQUANTITY, SALERATE, AMOUNT, CGSTRATE, SGSTRATE, IGSTRATE, UGSTRATE, VATRATE, CGSTAMOUNT, SGSTAMOUNT, IGSTAMOUNT, UGSTAMOUNT, VATAMOUNT, TOTALTAXAMOUNT, GROSSAMOUNT, GODOWNNUMBER, LOTNUMBER)
values (9, 6, 9, 20172018, 50, 3600, 180000, 2.5, 2.5, 0, 0, null, 4500, 4500, 0, 0, null, 9000, 189000, 2, 2);
insert into SALEINVOICEDETAIL (TRANSACTIONNUMBER, SERIALNUMBER, FINISHEDGOODSCODE, PRODUCTIONYEARCODE, SALEQUANTITY, SALERATE, AMOUNT, CGSTRATE, SGSTRATE, IGSTRATE, UGSTRATE, VATRATE, CGSTAMOUNT, SGSTAMOUNT, IGSTAMOUNT, UGSTAMOUNT, VATAMOUNT, TOTALTAXAMOUNT, GROSSAMOUNT, GODOWNNUMBER, LOTNUMBER)
values (10, 7, 9, 20172018, 100, 3600, 360000, 2.5, 2.5, 0, 0, null, 9000, 9000, 0, 0, null, 18000, 378000, 3, 1);
insert into SALEINVOICEDETAIL (TRANSACTIONNUMBER, SERIALNUMBER, FINISHEDGOODSCODE, PRODUCTIONYEARCODE, SALEQUANTITY, SALERATE, AMOUNT, CGSTRATE, SGSTRATE, IGSTRATE, UGSTRATE, VATRATE, CGSTAMOUNT, SGSTAMOUNT, IGSTAMOUNT, UGSTAMOUNT, VATAMOUNT, TOTALTAXAMOUNT, GROSSAMOUNT, GODOWNNUMBER, LOTNUMBER)
values (10, 8, 9, 20172018, 50, 3600, 180000, 2.5, 2.5, 0, 0, null, 4500, 4500, 0, 0, null, 9000, 189000, 3, 1);
insert into SALEINVOICEDETAIL (TRANSACTIONNUMBER, SERIALNUMBER, FINISHEDGOODSCODE, PRODUCTIONYEARCODE, SALEQUANTITY, SALERATE, AMOUNT, CGSTRATE, SGSTRATE, IGSTRATE, UGSTRATE, VATRATE, CGSTAMOUNT, SGSTAMOUNT, IGSTAMOUNT, UGSTAMOUNT, VATAMOUNT, TOTALTAXAMOUNT, GROSSAMOUNT, GODOWNNUMBER, LOTNUMBER)
values (11, 9, 9, 20172018, 100, 3600, 360000, 2.5, 2.5, 0, 0, null, 9000, 9000, 0, 0, null, 18000, 378000, 3, 1);
commit;
prompt 7 records loaded
prompt Loading SALEORDERHEADER...
insert into SALEORDERHEADER (TRANSACTIONNUMBER, SALEORDERNUMBER, SALEORDERNUMBERPRESUF, SALEORDERDATE, PURCHASERCODE, GOODSCATEGORYCODE, VALIDDATEOFLIFTING, YEARPERIODCODE, TENDERTRANSACTIONNUMBER, QUOTATIONTRANSACTIONNUMBER, SALECATEGORYCODE, SALEORDERNUMBERSERIESID, SALEORDERNUMBERBASEVALUE, REMARK)
values (1, 1, 'SO00001', to_date('09-03-2019', 'dd-mm-yyyy'), 1, 1, to_date('09-03-2019', 'dd-mm-yyyy'), 20182019, 2, 3, 1, null, 'Y20182019', null);
insert into SALEORDERHEADER (TRANSACTIONNUMBER, SALEORDERNUMBER, SALEORDERNUMBERPRESUF, SALEORDERDATE, PURCHASERCODE, GOODSCATEGORYCODE, VALIDDATEOFLIFTING, YEARPERIODCODE, TENDERTRANSACTIONNUMBER, QUOTATIONTRANSACTIONNUMBER, SALECATEGORYCODE, SALEORDERNUMBERSERIESID, SALEORDERNUMBERBASEVALUE, REMARK)
values (2, 2, 'SO00002', to_date('09-03-2019', 'dd-mm-yyyy'), 2, 1, to_date('15-03-2019', 'dd-mm-yyyy'), 20182019, 2, 4, 1, 3, 'Y20182019', null);
commit;
prompt 2 records loaded
prompt Loading SALETENDERHEADER...
insert into SALETENDERHEADER (TRANSACTIONNUMBER, YEARPERIODCODE, TENDERNUMBER, TENDERNUMBERPRESUF, TENDERDATE, PERMISSIONTRANSACTIONNUMBER, GOODSCATEGORYCODE, SALECATEGORYCODE, VALIDDATEOFLIFTING, TENDERCLOSEDATE, TENDERNUMBERSERIESID, TENDERNUMBERBASEVALUE, REMARK)
values (1, 20182019, 1, 'ST00001', to_date('01-03-2019', 'dd-mm-yyyy'), 1, 1, 1, to_date('15-03-2019', 'dd-mm-yyyy'), to_date('08-03-2019', 'dd-mm-yyyy'), 1, 'Y20182019', null);
insert into SALETENDERHEADER (TRANSACTIONNUMBER, YEARPERIODCODE, TENDERNUMBER, TENDERNUMBERPRESUF, TENDERDATE, PERMISSIONTRANSACTIONNUMBER, GOODSCATEGORYCODE, SALECATEGORYCODE, VALIDDATEOFLIFTING, TENDERCLOSEDATE, TENDERNUMBERSERIESID, TENDERNUMBERBASEVALUE, REMARK)
values (2, 20182019, 2, 'ST00002', to_date('18-03-2019', 'dd-mm-yyyy'), 1, 1, 1, to_date('30-03-2019', 'dd-mm-yyyy'), to_date('20-03-2019', 'dd-mm-yyyy'), 1, 'Y20182019', null);
commit;
prompt 2 records loaded
prompt Loading SALEMEMODETAIL...
insert into SALEMEMODETAIL (TRANSACTIONNUMBER, SERIALNUMBER, FINISHEDGOODSCODE, PRODUCTIONYEARCODE, SALEQUANTITY, SALERATE, AMOUNT, CGSTRATE, SGSTRATE, IGSTRATE, UGSTRATE, VATRATE, CGSTAMOUNT, SGSTAMOUNT, IGSTAMOUNT, UGSTAMOUNT, VATAMOUNT, TOTALTAXAMOUNT, GROSSAMOUNT, TENDERTRANSACTIONNUMBER, ORDERTRANSACTIONNUMBER)
values (4, 4, 9, 20172018, 50, 3600, 180000, 2.5, 2.5, 0, 0, null, 4500, 4500, 0, 0, null, 9000, 189000, 2, 2);
insert into SALEMEMODETAIL (TRANSACTIONNUMBER, SERIALNUMBER, FINISHEDGOODSCODE, PRODUCTIONYEARCODE, SALEQUANTITY, SALERATE, AMOUNT, CGSTRATE, SGSTRATE, IGSTRATE, UGSTRATE, VATRATE, CGSTAMOUNT, SGSTAMOUNT, IGSTAMOUNT, UGSTAMOUNT, VATAMOUNT, TOTALTAXAMOUNT, GROSSAMOUNT, TENDERTRANSACTIONNUMBER, ORDERTRANSACTIONNUMBER)
values (2, 2, 9, 20172018, 50, 3900, 195000, 2.5, 2.5, 0, 0, null, 4875, 4875, 0, 0, null, 9750, 204750, 2, 1);
insert into SALEMEMODETAIL (TRANSACTIONNUMBER, SERIALNUMBER, FINISHEDGOODSCODE, PRODUCTIONYEARCODE, SALEQUANTITY, SALERATE, AMOUNT, CGSTRATE, SGSTRATE, IGSTRATE, UGSTRATE, VATRATE, CGSTAMOUNT, SGSTAMOUNT, IGSTAMOUNT, UGSTAMOUNT, VATAMOUNT, TOTALTAXAMOUNT, GROSSAMOUNT, TENDERTRANSACTIONNUMBER, ORDERTRANSACTIONNUMBER)
values (7, 6, 9, 20172018, 200, 3900, 780000, 2.5, 2.5, 0, 0, null, 19500, 19500, 0, 0, null, 39000, 819000, 2, 1);
insert into SALEMEMODETAIL (TRANSACTIONNUMBER, SERIALNUMBER, FINISHEDGOODSCODE, PRODUCTIONYEARCODE, SALEQUANTITY, SALERATE, AMOUNT, CGSTRATE, SGSTRATE, IGSTRATE, UGSTRATE, VATRATE, CGSTAMOUNT, SGSTAMOUNT, IGSTAMOUNT, UGSTAMOUNT, VATAMOUNT, TOTALTAXAMOUNT, GROSSAMOUNT, TENDERTRANSACTIONNUMBER, ORDERTRANSACTIONNUMBER)
values (1, 1, 9, 20172018, 124, 3900, 483600, 2.5, 2.5, 0, 0, null, 12090, 12090, 0, 0, null, 24180, 507780, 2, 1);
insert into SALEMEMODETAIL (TRANSACTIONNUMBER, SERIALNUMBER, FINISHEDGOODSCODE, PRODUCTIONYEARCODE, SALEQUANTITY, SALERATE, AMOUNT, CGSTRATE, SGSTRATE, IGSTRATE, UGSTRATE, VATRATE, CGSTAMOUNT, SGSTAMOUNT, IGSTAMOUNT, UGSTAMOUNT, VATAMOUNT, TOTALTAXAMOUNT, GROSSAMOUNT, TENDERTRANSACTIONNUMBER, ORDERTRANSACTIONNUMBER)
values (3, 3, 9, 20172018, 600, 3600, 2160000, 2.5, 2.5, 0, 0, null, 54000, 54000, 0, 0, null, 108000, 2268000, 2, 2);
insert into SALEMEMODETAIL (TRANSACTIONNUMBER, SERIALNUMBER, FINISHEDGOODSCODE, PRODUCTIONYEARCODE, SALEQUANTITY, SALERATE, AMOUNT, CGSTRATE, SGSTRATE, IGSTRATE, UGSTRATE, VATRATE, CGSTAMOUNT, SGSTAMOUNT, IGSTAMOUNT, UGSTAMOUNT, VATAMOUNT, TOTALTAXAMOUNT, GROSSAMOUNT, TENDERTRANSACTIONNUMBER, ORDERTRANSACTIONNUMBER)
values (5, 5, 9, 20172018, 950, 3600, 3420000, 2.5, 2.5, 0, 0, null, 85500, 85500, 0, 0, null, 171000, 3591000, 2, 2);
commit;
prompt 6 records loaded
prompt Loading SALEORDERDETAIL...
insert into SALEORDERDETAIL (TRANSACTIONNUMBER, SERIALNUMBER, FINISHEDGOODSCODE, PRODUCTIONYEARCODE, ORDERQUANTITY, ORDERRATE)
values (1, 1, 9, 20172018, 1500, 3900);
insert into SALEORDERDETAIL (TRANSACTIONNUMBER, SERIALNUMBER, FINISHEDGOODSCODE, PRODUCTIONYEARCODE, ORDERQUANTITY, ORDERRATE)
values (1, 2, 8, 20182019, 1500, 4000);
insert into SALEORDERDETAIL (TRANSACTIONNUMBER, SERIALNUMBER, FINISHEDGOODSCODE, PRODUCTIONYEARCODE, ORDERQUANTITY, ORDERRATE)
values (2, 1, 9, 20172018, 1600, 3600);
commit;
prompt 3 records loaded
prompt Loading SALEQUOTATIONHEADER...
insert into SALEQUOTATIONHEADER (TRANSACTIONNUMBER, QUOTATIONNUMBER, QUOTATIONNUMBERPRESUF, QUOTATIONDATE, VALIDDATEOFLIFTING, PURCHASERCODE, PURCHASERQUOTATIONNUMBER, YEARPERIODCODE, GOODSCATEGORYCODE, SALECATEGORYCODE, TENDERTRANSACTIONNUMBER, QUOTATIONNUMBERSERIESID, QUOTATIONNUMBERBASEVALUE, REMARK)
values (3, 3, 'SQ00003', to_date('09-03-2019', 'dd-mm-yyyy'), to_date('15-03-2019', 'dd-mm-yyyy'), 1, 2451, 20182019, 1, 1, 2, 2, 'M032019', null);
insert into SALEQUOTATIONHEADER (TRANSACTIONNUMBER, QUOTATIONNUMBER, QUOTATIONNUMBERPRESUF, QUOTATIONDATE, VALIDDATEOFLIFTING, PURCHASERCODE, PURCHASERQUOTATIONNUMBER, YEARPERIODCODE, GOODSCATEGORYCODE, SALECATEGORYCODE, TENDERTRANSACTIONNUMBER, QUOTATIONNUMBERSERIESID, QUOTATIONNUMBERBASEVALUE, REMARK)
values (4, 4, 'SQ00004', to_date('09-03-2019', 'dd-mm-yyyy'), to_date('15-03-2019', 'dd-mm-yyyy'), 2, 2445, 20182019, 1, 1, 2, 2, 'M032019', null);
insert into SALEQUOTATIONHEADER (TRANSACTIONNUMBER, QUOTATIONNUMBER, QUOTATIONNUMBERPRESUF, QUOTATIONDATE, VALIDDATEOFLIFTING, PURCHASERCODE, PURCHASERQUOTATIONNUMBER, YEARPERIODCODE, GOODSCATEGORYCODE, SALECATEGORYCODE, TENDERTRANSACTIONNUMBER, QUOTATIONNUMBERSERIESID, QUOTATIONNUMBERBASEVALUE, REMARK)
values (1, 1, 'SQ00001', to_date('09-03-2019', 'dd-mm-yyyy'), to_date('15-03-2019', 'dd-mm-yyyy'), 2, null, 20182019, 1, 1, 1, 2, 'M032019', null);
insert into SALEQUOTATIONHEADER (TRANSACTIONNUMBER, QUOTATIONNUMBER, QUOTATIONNUMBERPRESUF, QUOTATIONDATE, VALIDDATEOFLIFTING, PURCHASERCODE, PURCHASERQUOTATIONNUMBER, YEARPERIODCODE, GOODSCATEGORYCODE, SALECATEGORYCODE, TENDERTRANSACTIONNUMBER, QUOTATIONNUMBERSERIESID, QUOTATIONNUMBERBASEVALUE, REMARK)
values (2, 2, 'SQ00002', to_date('09-03-2019', 'dd-mm-yyyy'), to_date('15-03-2019', 'dd-mm-yyyy'), 1, 2143, 20182019, 1, 1, 1, 2, 'M032019', null);
commit;
prompt 4 records loaded
prompt Loading SALEQUOTATIONDETAIL...
insert into SALEQUOTATIONDETAIL (TRANSACTIONNUMBER, SERIALNUMBER, FINISHEDGOODSCODE, PRODUCTIONYEARCODE, QUOTATIONQUANTITY, QUOTATIONRATE)
values (3, 1, 9, 20172018, 1600, 3900);
insert into SALEQUOTATIONDETAIL (TRANSACTIONNUMBER, SERIALNUMBER, FINISHEDGOODSCODE, PRODUCTIONYEARCODE, QUOTATIONQUANTITY, QUOTATIONRATE)
values (3, 2, 8, 20182019, 1500, 4000);
insert into SALEQUOTATIONDETAIL (TRANSACTIONNUMBER, SERIALNUMBER, FINISHEDGOODSCODE, PRODUCTIONYEARCODE, QUOTATIONQUANTITY, QUOTATIONRATE)
values (4, 1, 9, 20172018, 1600, 3600);
insert into SALEQUOTATIONDETAIL (TRANSACTIONNUMBER, SERIALNUMBER, FINISHEDGOODSCODE, PRODUCTIONYEARCODE, QUOTATIONQUANTITY, QUOTATIONRATE)
values (4, 2, 8, 20182019, 1500, null);
insert into SALEQUOTATIONDETAIL (TRANSACTIONNUMBER, SERIALNUMBER, FINISHEDGOODSCODE, PRODUCTIONYEARCODE, QUOTATIONQUANTITY, QUOTATIONRATE)
values (2, 1, 1, 20172018, 1000, 3750);
insert into SALEQUOTATIONDETAIL (TRANSACTIONNUMBER, SERIALNUMBER, FINISHEDGOODSCODE, PRODUCTIONYEARCODE, QUOTATIONQUANTITY, QUOTATIONRATE)
values (2, 2, 9, 20182019, 500, 3850);
insert into SALEQUOTATIONDETAIL (TRANSACTIONNUMBER, SERIALNUMBER, FINISHEDGOODSCODE, PRODUCTIONYEARCODE, QUOTATIONQUANTITY, QUOTATIONRATE)
values (1, 1, 1, 20172018, 1000, 3700);
insert into SALEQUOTATIONDETAIL (TRANSACTIONNUMBER, SERIALNUMBER, FINISHEDGOODSCODE, PRODUCTIONYEARCODE, QUOTATIONQUANTITY, QUOTATIONRATE)
values (1, 2, 9, 20182019, 500, 3800);
commit;
prompt 8 records loaded
prompt Loading SALETENDERDETAIL...
insert into SALETENDERDETAIL (TRANSACTIONNUMBER, SERIALNUMBER, FINISHEDGOODSCODE, PRODUCTIONYEARCODE, TENDERQUANTITY)
values (1, 1, 1, 20172018, 1000);
insert into SALETENDERDETAIL (TRANSACTIONNUMBER, SERIALNUMBER, FINISHEDGOODSCODE, PRODUCTIONYEARCODE, TENDERQUANTITY)
values (1, 2, 9, 20182019, 500);
insert into SALETENDERDETAIL (TRANSACTIONNUMBER, SERIALNUMBER, FINISHEDGOODSCODE, PRODUCTIONYEARCODE, TENDERQUANTITY)
values (2, 1, 9, 20172018, 1600);
insert into SALETENDERDETAIL (TRANSACTIONNUMBER, SERIALNUMBER, FINISHEDGOODSCODE, PRODUCTIONYEARCODE, TENDERQUANTITY)
values (2, 2, 8, 20182019, 1500);
commit;
prompt 4 records loaded
prompt Enabling foreign key constraints for GOODSSUBCATEGORY...
alter table GOODSSUBCATEGORY enable constraint FKCATCODE;
prompt Enabling foreign key constraints for FINISHEDGOODS...
alter table FINISHEDGOODS enable constraint FKGSUBCAT;
prompt Enabling foreign key constraints for GOODSPURCHASER...
alter table GOODSPURCHASER enable constraint FKGOODSPURCHASECD;
prompt Enabling foreign key constraints for GOODSTAXRATE...
alter table GOODSTAXRATE enable constraint FK112;
alter table GOODSTAXRATE enable constraint FKSALCATCD;
prompt Enabling foreign key constraints for SALENUMBERSERIES...
alter table SALENUMBERSERIES enable constraint FKGODCATCD;
alter table SALENUMBERSERIES enable constraint FKPERRESCAT;
prompt Enabling foreign key constraints for SALEMEMOHEADER...
alter table SALEMEMOHEADER enable constraint FKBRGODCAT;
alter table SALEMEMOHEADER enable constraint FKMENS;
alter table SALEMEMOHEADER enable constraint FKPURGODCAT;
alter table SALEMEMOHEADER enable constraint FKSHIPGODCAT;
prompt Enabling foreign key constraints for SALEINVOICEHEADER...
alter table SALEINVOICEHEADER enable constraint FKBRGC;
alter table SALEINVOICEHEADER enable constraint FKGOODSCAT;
alter table SALEINVOICEHEADER enable constraint FKNUMSERID;
alter table SALEINVOICEHEADER enable constraint FKSALECAT;
alter table SALEINVOICEHEADER enable constraint FKSALEMEMOTRNNUM;
prompt Enabling foreign key constraints for SALEACCOUNTBRIDGE...
alter table SALEACCOUNTBRIDGE enable constraint FKACTRNNO;
alter table SALEACCOUNTBRIDGE enable constraint FKSLTRNNO;
prompt Enabling foreign key constraints for SALEINVOICEDETAIL...
alter table SALEINVOICEDETAIL enable constraint FK76878;
alter table SALEINVOICEDETAIL enable constraint FKFINGODCD;
alter table SALEINVOICEDETAIL enable constraint FKGODNUM;
prompt Enabling foreign key constraints for SALEORDERHEADER...
alter table SALEORDERHEADER enable constraint FKPURCD1;
alter table SALEORDERHEADER enable constraint FKSALCAT;
prompt Enabling foreign key constraints for SALETENDERHEADER...
alter table SALETENDERHEADER enable constraint FKGODCATCD6;
alter table SALETENDERHEADER enable constraint FKPER;
alter table SALETENDERHEADER enable constraint FKSALECATCD2;
alter table SALETENDERHEADER enable constraint FKSALNUMSER3;
prompt Enabling foreign key constraints for SALEMEMODETAIL...
alter table SALEMEMODETAIL enable constraint FKFINGOODS;
alter table SALEMEMODETAIL enable constraint FKORTRAN;
alter table SALEMEMODETAIL enable constraint FKTENTRAN;
alter table SALEMEMODETAIL enable constraint FKTRNNO;
prompt Enabling foreign key constraints for SALEORDERDETAIL...
alter table SALEORDERDETAIL enable constraint FKFINGODCD1;
alter table SALEORDERDETAIL enable constraint FKSOH;
prompt Enabling foreign key constraints for SALEQUOTATIONHEADER...
alter table SALEQUOTATIONHEADER enable constraint FKPURGDCT;
alter table SALEQUOTATIONHEADER enable constraint FKQUOTNUM;
alter table SALEQUOTATIONHEADER enable constraint FKSALCATCD4;
prompt Enabling foreign key constraints for SALEQUOTATIONDETAIL...
alter table SALEQUOTATIONDETAIL enable constraint FKFINGODCD2;
alter table SALEQUOTATIONDETAIL enable constraint FKTRNHEA;
prompt Enabling foreign key constraints for SALETENDERDETAIL...
alter table SALETENDERDETAIL enable constraint FKFINGOD5;
alter table SALETENDERDETAIL enable constraint TRNTENDHE;
prompt Enabling triggers for GOODSCATEGORY...
alter table GOODSCATEGORY enable all triggers;
prompt Enabling triggers for GOODSSUBCATEGORY...
alter table GOODSSUBCATEGORY enable all triggers;
prompt Enabling triggers for FINISHEDGOODS...
alter table FINISHEDGOODS enable all triggers;
prompt Enabling triggers for GODOWN...
alter table GODOWN enable all triggers;
prompt Enabling triggers for PURCHASERCATEGORY...
alter table PURCHASERCATEGORY enable all triggers;
prompt Enabling triggers for GOODSPURCHASER...
alter table GOODSPURCHASER enable all triggers;
prompt Enabling triggers for GOODSSALEPERMISSION...
alter table GOODSSALEPERMISSION enable all triggers;
prompt Enabling triggers for GOODSSALETAXES...
alter table GOODSSALETAXES enable all triggers;
prompt Enabling triggers for SALECATEGORY...
alter table SALECATEGORY enable all triggers;
prompt Enabling triggers for GOODSTAXRATE...
alter table GOODSTAXRATE enable all triggers;
prompt Enabling triggers for PERIODRESETCATEGORY...
alter table PERIODRESETCATEGORY enable all triggers;
prompt Enabling triggers for SALENUMBERSERIES...
alter table SALENUMBERSERIES enable all triggers;
prompt Enabling triggers for SALEMEMOHEADER...
alter table SALEMEMOHEADER enable all triggers;
prompt Enabling triggers for SALEINVOICEHEADER...
alter table SALEINVOICEHEADER enable all triggers;
prompt Enabling triggers for SALEACCOUNTBRIDGE...
alter table SALEACCOUNTBRIDGE enable all triggers;
prompt Enabling triggers for SALECONTROLTABLE...
alter table SALECONTROLTABLE enable all triggers;
prompt Enabling triggers for SALEINVOICEDETAIL...
alter table SALEINVOICEDETAIL enable all triggers;
prompt Enabling triggers for SALEORDERHEADER...
alter table SALEORDERHEADER enable all triggers;
prompt Enabling triggers for SALETENDERHEADER...
alter table SALETENDERHEADER enable all triggers;
prompt Enabling triggers for SALEMEMODETAIL...
alter table SALEMEMODETAIL enable all triggers;
prompt Enabling triggers for SALEORDERDETAIL...
alter table SALEORDERDETAIL enable all triggers;
prompt Enabling triggers for SALEQUOTATIONHEADER...
alter table SALEQUOTATIONHEADER enable all triggers;
prompt Enabling triggers for SALEQUOTATIONDETAIL...
alter table SALEQUOTATIONDETAIL enable all triggers;
prompt Enabling triggers for SALETENDERDETAIL...
alter table SALETENDERDETAIL enable all triggers;
set feedback on
set define on
prompt Done.
