----------------------------------------------
-- Export file for user nst_nasaka_FINANCE --
-- Created by admin on 29/03/2019, 20:48:57 --
----------------------------------------------

spool finobj_290319.log

prompt
prompt Creating table AAAA
prompt ===================
prompt
create table nst_nasaka_FINANCE.AAAA
(
  NIND_TYPE         NUMBER,
  VIND_CODE         VARCHAR2(41),
  VIND_NAME         VARCHAR2(200),
  VIND_NAME_MARATHI VARCHAR2(506),
  VADDRESS          VARCHAR2(250),
  NMULTIPLE_ACIVITY NUMBER,
  SR_NO             NUMBER,
  VIND_NAME_UNICODE VARCHAR2(2000)
)
;

prompt
prompt Creating table ACCOUNTCONTROLTABLE
prompt ==================================
prompt
create table nst_nasaka_FINANCE.ACCOUNTCONTROLTABLE
(
  CASHACCOUNTCODE              NUMBER,
  VOUCHERLOCKDATE              DATE,
  BANKGROUPCODE                NUMBER,
  BANKSUBGROUPCODE             NUMBER,
  BANKGROUPCODE1               NUMBER,
  BANKGROUPCODE2               NUMBER,
  ACCRUEDPROFITLOSSACCOUNTCODE NUMBER
)
;

prompt
prompt Creating table GROUPNATURE
prompt ==========================
prompt
create table nst_nasaka_FINANCE.GROUPNATURE
(
  GROUPNATURECODE NUMBER not null,
  GROUPNATUREENG  VARCHAR2(150),
  GROUPNATUREUNI  VARCHAR2(150)
)
;
alter table nst_nasaka_FINANCE.GROUPNATURE
  add constraint PKGROUPNATURECODE primary key (GROUPNATURECODE);
grant select on nst_nasaka_FINANCE.GROUPNATURE to nst_nasaka_DB;

prompt
prompt Creating table GROUPTYPE
prompt ========================
prompt
create table nst_nasaka_FINANCE.GROUPTYPE
(
  GROUPTYPECODE    NUMBER not null,
  GROUPTYPENAMEENG VARCHAR2(200),
  GROUPTYPENAMEUNI VARCHAR2(200),
  GROUPNATURECODE  NUMBER
)
;
alter table nst_nasaka_FINANCE.GROUPTYPE
  add constraint PKGROUPTYPECODE primary key (GROUPTYPECODE);
alter table nst_nasaka_FINANCE.GROUPTYPE
  add constraint FKGROUPNATURECODE foreign key (GROUPNATURECODE)
  references nst_nasaka_FINANCE.GROUPNATURE (GROUPNATURECODE);
grant select on nst_nasaka_FINANCE.GROUPTYPE to nst_nasaka_DB;

prompt
prompt Creating table ACCOUNTGROUP
prompt ===========================
prompt
create table nst_nasaka_FINANCE.ACCOUNTGROUP
(
  GROUPCODE       NUMBER not null,
  GROUPNAMEENG    VARCHAR2(300) not null,
  GROUPNAMEUNI    VARCHAR2(300) not null,
  GROUPTYPECODE   NUMBER not null,
  REFCODE         NUMBER,
  LEGALENTITYCODE NUMBER not null,
  SEQUENCENUMBER  NUMBER
)
;
alter table nst_nasaka_FINANCE.ACCOUNTGROUP
  add constraint PKGROUPCODE primary key (GROUPCODE);
alter table nst_nasaka_FINANCE.ACCOUNTGROUP
  add constraint FKGROUPTYPECODE foreign key (GROUPTYPECODE)
  references nst_nasaka_FINANCE.GROUPTYPE (GROUPTYPECODE);
grant select on nst_nasaka_FINANCE.ACCOUNTGROUP to nst_nasaka_DB;

prompt
prompt Creating table ACCOUNTSCHEDULE
prompt ==============================
prompt
create table nst_nasaka_FINANCE.ACCOUNTSCHEDULE
(
  SCHEDULECODE    NUMBER not null,
  SCHEDULENAMEENG VARCHAR2(300) not null,
  SCHEDULENAMEUNI VARCHAR2(300) not null,
  GROUPTYPECODE   NUMBER not null,
  SCHEDULENUMBER  NUMBER,
  GROUPCODE       NUMBER,
  SUBGROUPCODE    NUMBER,
  SUBSUBGROUPCODE NUMBER,
  LEGALENTITYCODE NUMBER not null
)
;
alter table nst_nasaka_FINANCE.ACCOUNTSCHEDULE
  add constraint PKSCHEDULECODE primary key (SCHEDULECODE);
grant select on nst_nasaka_FINANCE.ACCOUNTSCHEDULE to nst_nasaka_DB;

prompt
prompt Creating table ACCOUNTSUBGROUP
prompt ==============================
prompt
create table nst_nasaka_FINANCE.ACCOUNTSUBGROUP
(
  SUBGROUPCODE    NUMBER not null,
  SUBGROUPNAMEENG VARCHAR2(150) not null,
  SUBGROUPNAMEUNI VARCHAR2(150) not null,
  GROUPCODE       NUMBER not null,
  SEQUENCENUMBER  NUMBER
)
;
alter table nst_nasaka_FINANCE.ACCOUNTSUBGROUP
  add constraint PKSUBGROUPCODE primary key (SUBGROUPCODE);
alter table nst_nasaka_FINANCE.ACCOUNTSUBGROUP
  add constraint FKGROUPCODE foreign key (GROUPCODE)
  references nst_nasaka_FINANCE.ACCOUNTGROUP (GROUPCODE);
grant select on nst_nasaka_FINANCE.ACCOUNTSUBGROUP to nst_nasaka_DB;

prompt
prompt Creating table ACCOUNTSUBSCHEDULE
prompt =================================
prompt
create table nst_nasaka_FINANCE.ACCOUNTSUBSCHEDULE
(
  SUBSCHEDULECODE    NUMBER not null,
  SUBSCHEDULENAMEENG VARCHAR2(150) not null,
  SUBSCHEDULENAMEUNI VARCHAR2(150) not null,
  SCHEDULECODE       NUMBER not null
)
;
alter table nst_nasaka_FINANCE.ACCOUNTSUBSCHEDULE
  add constraint PKSUBSCHEDULECODE primary key (SUBSCHEDULECODE);
alter table nst_nasaka_FINANCE.ACCOUNTSUBSCHEDULE
  add constraint FKSCCODE foreign key (SCHEDULECODE)
  references nst_nasaka_FINANCE.ACCOUNTSCHEDULE (SCHEDULECODE);
grant select on nst_nasaka_FINANCE.ACCOUNTSUBSCHEDULE to nst_nasaka_DB;

prompt
prompt Creating table ACCOUNTSUBSUBGROUP
prompt =================================
prompt
create table nst_nasaka_FINANCE.ACCOUNTSUBSUBGROUP
(
  SUBSUBGROUPCODE    NUMBER not null,
  SUBSUBGROUPNAMEENG VARCHAR2(150) not null,
  SUBSUBGROUPNAMEUNI VARCHAR2(150) not null,
  SUBGROUPCODE       NUMBER not null,
  GROUPCODE          NUMBER
)
;
alter table nst_nasaka_FINANCE.ACCOUNTSUBSUBGROUP
  add constraint PKSUBSUBGROUPCODE primary key (SUBSUBGROUPCODE);
alter table nst_nasaka_FINANCE.ACCOUNTSUBSUBGROUP
  add constraint FKSUBGROUPCODE foreign key (SUBGROUPCODE)
  references nst_nasaka_FINANCE.ACCOUNTSUBGROUP (SUBGROUPCODE);
grant select on nst_nasaka_FINANCE.ACCOUNTSUBSUBGROUP to nst_nasaka_DB;

prompt
prompt Creating table ACCOUNTSUBSUBSCHEDULE
prompt ====================================
prompt
create table nst_nasaka_FINANCE.ACCOUNTSUBSUBSCHEDULE
(
  SUBSUBSCHEDULECODE    NUMBER not null,
  SUBSUBSCHEDULENAMEENG VARCHAR2(150) not null,
  SUBSUBSCHEDULENAMEUNI VARCHAR2(150) not null,
  SUBSCHEDULECODE       NUMBER not null,
  SCHEDULECODE          NUMBER
)
;
alter table nst_nasaka_FINANCE.ACCOUNTSUBSUBSCHEDULE
  add constraint PKSUBSUBSCHEDULECODE primary key (SUBSUBSCHEDULECODE);
alter table nst_nasaka_FINANCE.ACCOUNTSUBSUBSCHEDULE
  add constraint FKSUBSCHEDULECODE foreign key (SUBSCHEDULECODE)
  references nst_nasaka_FINANCE.ACCOUNTSUBSCHEDULE (SUBSCHEDULECODE);
grant select on nst_nasaka_FINANCE.ACCOUNTSUBSUBSCHEDULE to nst_nasaka_DB;

prompt
prompt Creating table ACCOUNTHEAD
prompt ==========================
prompt
create table nst_nasaka_FINANCE.ACCOUNTHEAD
(
  ACCOUNTCODE           NUMBER not null,
  ACCOUNTNAMEENG        VARCHAR2(300) not null,
  ACCOUNTNAMEUNI        VARCHAR2(300) not null,
  GROUPCODE             NUMBER not null,
  SUBGROUPCODE          NUMBER,
  SUBSUBGROUPCODE       NUMBER,
  SCHEDULECODE          NUMBER,
  SUBSCHEDULECODE       NUMBER,
  SUBSUBSCHEDULECODE    NUMBER,
  ISSUBLEDGERALLOWED    NUMBER not null,
  REFCODE               VARCHAR2(20),
  SUBSUBSUBSCHEDULECODE NUMBER
)
;
alter table nst_nasaka_FINANCE.ACCOUNTHEAD
  add constraint PKACCOUNTCODE primary key (ACCOUNTCODE);
alter table nst_nasaka_FINANCE.ACCOUNTHEAD
  add constraint FKGCD foreign key (GROUPCODE)
  references nst_nasaka_FINANCE.ACCOUNTGROUP (GROUPCODE);
alter table nst_nasaka_FINANCE.ACCOUNTHEAD
  add constraint FKSC foreign key (SCHEDULECODE)
  references nst_nasaka_FINANCE.ACCOUNTSCHEDULE (SCHEDULECODE);
alter table nst_nasaka_FINANCE.ACCOUNTHEAD
  add constraint FKSCSUB foreign key (SUBSCHEDULECODE)
  references nst_nasaka_FINANCE.ACCOUNTSUBSCHEDULE (SUBSCHEDULECODE)
  disable;
alter table nst_nasaka_FINANCE.ACCOUNTHEAD
  add constraint FKSCSUSUB foreign key (SUBSUBSCHEDULECODE)
  references nst_nasaka_FINANCE.ACCOUNTSUBSUBSCHEDULE (SUBSUBSCHEDULECODE);
alter table nst_nasaka_FINANCE.ACCOUNTHEAD
  add constraint FKSUB foreign key (SUBGROUPCODE)
  references nst_nasaka_FINANCE.ACCOUNTSUBGROUP (SUBGROUPCODE);
alter table nst_nasaka_FINANCE.ACCOUNTHEAD
  add constraint FKSUBSUB foreign key (SUBSUBGROUPCODE)
  references nst_nasaka_FINANCE.ACCOUNTSUBSUBGROUP (SUBSUBGROUPCODE);
grant select on nst_nasaka_FINANCE.ACCOUNTHEAD to nst_nasaka_DB;

prompt
prompt Creating table SUBLEDGERTYPE
prompt ============================
prompt
create table nst_nasaka_FINANCE.SUBLEDGERTYPE
(
  SUBLEDGERTYPECODE    NUMBER not null,
  SUBLEDGERTYPENAMEENG VARCHAR2(200),
  SUBLEDGERTYPENAMEUNI VARCHAR2(200),
  PREFIX               VARCHAR2(1)
)
;
alter table nst_nasaka_FINANCE.SUBLEDGERTYPE
  add constraint PKSUBLEDGERTYPECODE primary key (SUBLEDGERTYPECODE);
alter table nst_nasaka_FINANCE.SUBLEDGERTYPE
  add constraint UQPRE unique (PREFIX);
grant select on nst_nasaka_FINANCE.SUBLEDGERTYPE to nst_nasaka_DB;

prompt
prompt Creating table ACCOUNTHEADDETAIL
prompt ================================
prompt
create table nst_nasaka_FINANCE.ACCOUNTHEADDETAIL
(
  ACCOUNTCODE       NUMBER not null,
  SUBLEDGERTYPECODE NUMBER not null
)
;
alter table nst_nasaka_FINANCE.ACCOUNTHEADDETAIL
  add constraint PKACCONTHEADDET primary key (ACCOUNTCODE, SUBLEDGERTYPECODE);
alter table nst_nasaka_FINANCE.ACCOUNTHEADDETAIL
  add constraint FKACC2 foreign key (ACCOUNTCODE)
  references nst_nasaka_FINANCE.ACCOUNTHEAD (ACCOUNTCODE);
alter table nst_nasaka_FINANCE.ACCOUNTHEADDETAIL
  add constraint FKSUBCD foreign key (SUBLEDGERTYPECODE)
  references nst_nasaka_FINANCE.SUBLEDGERTYPE (SUBLEDGERTYPECODE);
grant select on nst_nasaka_FINANCE.ACCOUNTHEADDETAIL to nst_nasaka_DB;

prompt
prompt Creating table ACCOUNTNOTEMASTER
prompt ================================
prompt
create table nst_nasaka_FINANCE.ACCOUNTNOTEMASTER
(
  NOTECODE  NUMBER not null,
  NOTEVALUE NUMBER
)
;
alter table nst_nasaka_FINANCE.ACCOUNTNOTEMASTER
  add constraint PKNOTEMASTER primary key (NOTECODE);

prompt
prompt Creating table ACCOUNTOPENING
prompt =============================
prompt
create table nst_nasaka_FINANCE.ACCOUNTOPENING
(
  YEARPERIODCODE NUMBER not null,
  ENTITYCODE     NUMBER not null,
  ACCOUNTCODE    NUMBER not null,
  SUBLEDGERCODE  NUMBER,
  DEBITBALANCE   NUMBER,
  CREDITBALANCE  NUMBER
)
;
alter table nst_nasaka_FINANCE.ACCOUNTOPENING
  add constraint PKYRAC primary key (YEARPERIODCODE, ACCOUNTCODE);
grant select on nst_nasaka_FINANCE.ACCOUNTOPENING to nst_nasaka_DB;

prompt
prompt Creating table ACCOUNTSUBLEDGER
prompt ===============================
prompt
create table nst_nasaka_FINANCE.ACCOUNTSUBLEDGER
(
  SUBLEDGERCODE     NUMBER not null,
  SUBLEDGERNAMEENG  VARCHAR2(500) not null,
  SUBLEDGERNAMEUNI  VARCHAR2(500) not null,
  SUBLEDGERTYPECODE NUMBER not null,
  ACCOUNTCODE       NUMBER not null,
  REFERENCECODE     VARCHAR2(25),
  ISNEWRECORD       NUMBER default 0
)
;
alter table nst_nasaka_FINANCE.ACCOUNTSUBLEDGER
  add constraint PKSUBLEDGERCODE primary key (SUBLEDGERCODE, ACCOUNTCODE);
alter table nst_nasaka_FINANCE.ACCOUNTSUBLEDGER
  add constraint UQACCOUNTCODESUBLE unique (ACCOUNTCODE, SUBLEDGERTYPECODE, REFERENCECODE);
alter table nst_nasaka_FINANCE.ACCOUNTSUBLEDGER
  add constraint FKSUBLEDGERTYPE1 foreign key (SUBLEDGERTYPECODE)
  references nst_nasaka_FINANCE.SUBLEDGERTYPE (SUBLEDGERTYPECODE);
grant select on nst_nasaka_FINANCE.ACCOUNTSUBLEDGER to nst_nasaka_DB;

prompt
prompt Creating table ACCOUNTSUBSUBSUBSCHEDULE
prompt =======================================
prompt
create table nst_nasaka_FINANCE.ACCOUNTSUBSUBSUBSCHEDULE
(
  SUBSUBSUBSCHEDULECODE    NUMBER not null,
  SUBSUBSUBSCHEDULENAMEENG VARCHAR2(150) not null,
  SUBSUBSUBSCHEDULENAMEUNI VARCHAR2(150) not null,
  SCHEDULECODE             NUMBER not null,
  SUBSCHEDULECODE          NUMBER not null,
  SUBSUBSCHEDULECODE       NUMBER not null
)
;
alter table nst_nasaka_FINANCE.ACCOUNTSUBSUBSUBSCHEDULE
  add constraint PKSUBSUBSUBSCD primary key (SUBSUBSUBSCHEDULECODE);
alter table nst_nasaka_FINANCE.ACCOUNTSUBSUBSUBSCHEDULE
  add constraint FKSCHCD4 foreign key (SCHEDULECODE)
  references nst_nasaka_FINANCE.ACCOUNTSCHEDULE (SCHEDULECODE);
alter table nst_nasaka_FINANCE.ACCOUNTSUBSUBSUBSCHEDULE
  add constraint FKSUBSCHCD4 foreign key (SUBSCHEDULECODE)
  references nst_nasaka_FINANCE.ACCOUNTSUBSCHEDULE (SUBSCHEDULECODE);
alter table nst_nasaka_FINANCE.ACCOUNTSUBSUBSUBSCHEDULE
  add constraint FKSUBSUBSCHCD4 foreign key (SUBSUBSCHEDULECODE)
  references nst_nasaka_FINANCE.ACCOUNTSUBSUBSCHEDULE (SUBSUBSCHEDULECODE);

prompt
prompt Creating table ACCOUNTVOUCHERACTION
prompt ===================================
prompt
create table nst_nasaka_FINANCE.ACCOUNTVOUCHERACTION
(
  ACTIONCODE    NUMBER not null,
  ACTIONNAMEENG VARCHAR2(100),
  ACTIONNAMEUNI VARCHAR2(100)
)
;
alter table nst_nasaka_FINANCE.ACCOUNTVOUCHERACTION
  add constraint ACTIONCODE primary key (ACTIONCODE);

prompt
prompt Creating table SUPPORTINGDOCUMENTTYPE
prompt =====================================
prompt
create table nst_nasaka_FINANCE.SUPPORTINGDOCUMENTTYPE
(
  SUPPORTINGDOCUMENTTYPECODE NUMBER not null,
  SUPPORTINGDOCUMENTNAMEENG  VARCHAR2(100),
  SUPPORTINGDOCUMENTNAMEUNI  VARCHAR2(100)
)
;
alter table nst_nasaka_FINANCE.SUPPORTINGDOCUMENTTYPE
  add constraint PKSUPDOCCODE primary key (SUPPORTINGDOCUMENTTYPECODE);
grant select on nst_nasaka_FINANCE.SUPPORTINGDOCUMENTTYPE to nst_nasaka_DB;

prompt
prompt Creating table APPLICATIONDETAIL
prompt ================================
prompt
create table nst_nasaka_FINANCE.APPLICATIONDETAIL
(
  APPLICATIONNUMBER      NUMBER not null,
  SERIALNUMBER           NUMBER not null,
  SUPPORTINGDOCUMENTTYPE NUMBER not null
)
;
alter table nst_nasaka_FINANCE.APPLICATIONDETAIL
  add constraint PKSUPDOC primary key (APPLICATIONNUMBER, SERIALNUMBER);
alter table nst_nasaka_FINANCE.APPLICATIONDETAIL
  add constraint FKSUPDOCTY foreign key (SUPPORTINGDOCUMENTTYPE)
  references nst_nasaka_FINANCE.SUPPORTINGDOCUMENTTYPE (SUPPORTINGDOCUMENTTYPECODE);
grant select on nst_nasaka_FINANCE.APPLICATIONDETAIL to nst_nasaka_DB;

prompt
prompt Creating table APPLICATIONSTATUS
prompt ================================
prompt
create table nst_nasaka_FINANCE.APPLICATIONSTATUS
(
  APPLICATIONSTATUSCODE    NUMBER not null,
  APPLICATIONSTATUSNAMEENG VARCHAR2(200) not null,
  APPLICATIONSTATUSNAMEUNI VARCHAR2(200) not null
)
;
alter table nst_nasaka_FINANCE.APPLICATIONSTATUS
  add constraint PKAPPLICATIONSTATUSCODE primary key (APPLICATIONSTATUSCODE);
grant select on nst_nasaka_FINANCE.APPLICATIONSTATUS to nst_nasaka_DB;

prompt
prompt Creating table APPLICATIONHEADER
prompt ================================
prompt
create table nst_nasaka_FINANCE.APPLICATIONHEADER
(
  APPLICATIONNUMBER     NUMBER not null,
  APPLICATIONDATE       DATE not null,
  APPLICANT             VARCHAR2(500) not null,
  PURPOSE               VARCHAR2(500) not null,
  REMARKHOD             VARCHAR2(500),
  REMARKMDSCCH          VARCHAR2(500),
  APPLICATIONSTATUSCODE NUMBER
)
;
alter table nst_nasaka_FINANCE.APPLICATIONHEADER
  add constraint PKTRANSACTIONNUMBERHD primary key (APPLICATIONNUMBER);
alter table nst_nasaka_FINANCE.APPLICATIONHEADER
  add constraint FKAPPLICATIONSTATUS foreign key (APPLICATIONSTATUSCODE)
  references nst_nasaka_FINANCE.APPLICATIONSTATUS (APPLICATIONSTATUSCODE);
grant select on nst_nasaka_FINANCE.APPLICATIONHEADER to nst_nasaka_DB;

prompt
prompt Creating table LEGALENTITY
prompt ==========================
prompt
create table nst_nasaka_FINANCE.LEGALENTITY
(
  LEGALENTITYCODE    NUMBER not null,
  LEGALENTITYNAMEENG VARCHAR2(200),
  LEGALENTITYNAMEUNI VARCHAR2(200)
)
;
alter table nst_nasaka_FINANCE.LEGALENTITY
  add constraint PKLEGALENTITYCODE primary key (LEGALENTITYCODE);
grant select on nst_nasaka_FINANCE.LEGALENTITY to nst_nasaka_DB;

prompt
prompt Creating table BUDGET
prompt =====================
prompt
create table nst_nasaka_FINANCE.BUDGET
(
  YEARCODE     VARCHAR2(9) not null,
  ENTITYCODE   NUMBER not null,
  ACCOUNTCODE  NUMBER not null,
  CREDITBUDGET NUMBER,
  DEBITBUDGET  NUMBER,
  NARRATION    VARCHAR2(300)
)
;
alter table nst_nasaka_FINANCE.BUDGET
  add constraint PKBUDGET primary key (YEARCODE, ENTITYCODE, ACCOUNTCODE);
alter table nst_nasaka_FINANCE.BUDGET
  add constraint FKBUDENTITYCODE foreign key (ENTITYCODE)
  references nst_nasaka_FINANCE.LEGALENTITY (LEGALENTITYCODE);
grant select on nst_nasaka_FINANCE.BUDGET to nst_nasaka_DB;

prompt
prompt Creating table COSTCENTRECAT
prompt ============================
prompt
create table nst_nasaka_FINANCE.COSTCENTRECAT
(
  COSTCENTRECATCODE    NUMBER not null,
  COSTCENTRECATNAMEENG VARCHAR2(300),
  COSTCENTRECATNAMEUNI VARCHAR2(300)
)
;
alter table nst_nasaka_FINANCE.COSTCENTRECAT
  add constraint PKCOSTCENTRECATCODE primary key (COSTCENTRECATCODE);
grant select on nst_nasaka_FINANCE.COSTCENTRECAT to nst_nasaka_DB;

prompt
prompt Creating table COSTCENTRESUBCAT
prompt ===============================
prompt
create table nst_nasaka_FINANCE.COSTCENTRESUBCAT
(
  COSTCENTRESUBCATCODE    NUMBER not null,
  COSTCENTRESUBCATNAMEENG VARCHAR2(150),
  COSTCENTRESUBCATNAMEUNI VARCHAR2(150),
  COSTCENTRECATCODE       NUMBER
)
;
alter table nst_nasaka_FINANCE.COSTCENTRESUBCAT
  add constraint PKSUBCOSTCENTRECATCODE primary key (COSTCENTRESUBCATCODE);
alter table nst_nasaka_FINANCE.COSTCENTRESUBCAT
  add constraint FKCOSTCENTRECATCODE foreign key (COSTCENTRECATCODE)
  references nst_nasaka_FINANCE.COSTCENTRECAT (COSTCENTRECATCODE);
grant select on nst_nasaka_FINANCE.COSTCENTRESUBCAT to nst_nasaka_DB;

prompt
prompt Creating table COSTCENTRESUBSUBCAT
prompt ==================================
prompt
create table nst_nasaka_FINANCE.COSTCENTRESUBSUBCAT
(
  COSTCENTRESUBSUBCATCODE    NUMBER not null,
  COSTCENTRESUBSUBCATNAMEENG VARCHAR2(150),
  COSTCENTRESUBSUBCATNAMEUNI VARCHAR2(150),
  COSTCENTRESUBCATCODE       NUMBER
)
;
alter table nst_nasaka_FINANCE.COSTCENTRESUBSUBCAT
  add constraint PKSUBSUBCOSTCENTRECATCODE primary key (COSTCENTRESUBSUBCATCODE);
alter table nst_nasaka_FINANCE.COSTCENTRESUBSUBCAT
  add constraint FKCOSTCENTRESUBCATCODE2 foreign key (COSTCENTRESUBCATCODE)
  references nst_nasaka_FINANCE.COSTCENTRESUBCAT (COSTCENTRESUBCATCODE);
grant select on nst_nasaka_FINANCE.COSTCENTRESUBSUBCAT to nst_nasaka_DB;

prompt
prompt Creating table COSTCENTREHEAD
prompt =============================
prompt
create table nst_nasaka_FINANCE.COSTCENTREHEAD
(
  COSTCENTRECODE          NUMBER not null,
  COSTCENTRENAMEENG       VARCHAR2(300) not null,
  COSTCENTRENAMEUNI       VARCHAR2(300) not null,
  COSTCENTRECATCODE       NUMBER not null,
  COSTCENTRESUBCATCODE    NUMBER,
  COSTCENTRESUBSUBCATCODE NUMBER
)
;
alter table nst_nasaka_FINANCE.COSTCENTREHEAD
  add constraint PKCOSTCENTRECODE primary key (COSTCENTRECODE);
alter table nst_nasaka_FINANCE.COSTCENTREHEAD
  add constraint FKCOSTCENTRECATCODE1 foreign key (COSTCENTRECATCODE)
  references nst_nasaka_FINANCE.COSTCENTRECAT (COSTCENTRECATCODE);
alter table nst_nasaka_FINANCE.COSTCENTREHEAD
  add constraint FKCOSTCENTRESUBCATCODE11 foreign key (COSTCENTRESUBCATCODE)
  references nst_nasaka_FINANCE.COSTCENTRESUBCAT (COSTCENTRESUBCATCODE);
alter table nst_nasaka_FINANCE.COSTCENTREHEAD
  add constraint FKCOSTCENTRESUBSUBCATCODE11 foreign key (COSTCENTRESUBSUBCATCODE)
  references nst_nasaka_FINANCE.COSTCENTRESUBSUBCAT (COSTCENTRESUBSUBCATCODE);
grant select on nst_nasaka_FINANCE.COSTCENTREHEAD to nst_nasaka_DB;

prompt
prompt Creating table DEPRECIATION
prompt ===========================
prompt
create table nst_nasaka_FINANCE.DEPRECIATION
(
  YEARCODE             VARCHAR2(9) not null,
  ENTITYCODE           NUMBER not null,
  ACCOUNTCODE          NUMBER not null,
  DEPRECIATIONOPENING  NUMBER,
  FIRSTHALFYEARCREDIT  NUMBER,
  SECONDHALFYEARCREDIT NUMBER,
  FIRSTHALFYEARDEBIT   NUMBER,
  SECONDHALFYEARDEBIT  NUMBER,
  DEPRECIATIONPERCENT  NUMBER,
  LEDGEROPENINGCREDIT  NUMBER,
  LEDGEROPENINGDEBIT   NUMBER,
  CURRENTDEPRECIATION  NUMBER,
  CLOSINGDEPRECIATION  NUMBER
)
;
alter table nst_nasaka_FINANCE.DEPRECIATION
  add constraint PKDEPRECIATION primary key (YEARCODE, ENTITYCODE, ACCOUNTCODE);
grant select on nst_nasaka_FINANCE.DEPRECIATION to nst_nasaka_DB;

prompt
prompt Creating table FUNDCATEGORY
prompt ===========================
prompt
create table nst_nasaka_FINANCE.FUNDCATEGORY
(
  FUNDCATEGORYCODE    NUMBER not null,
  FUNDCATEGORYNAMEENG VARCHAR2(50),
  FUNDCATEGORYNAMEUNI VARCHAR2(50)
)
;
alter table nst_nasaka_FINANCE.FUNDCATEGORY
  add constraint PKFUNFCATCD primary key (FUNDCATEGORYCODE);
grant select on nst_nasaka_FINANCE.FUNDCATEGORY to nst_nasaka_DB;

prompt
prompt Creating table FUNDDOCUMENTTYPE
prompt ===============================
prompt
create table nst_nasaka_FINANCE.FUNDDOCUMENTTYPE
(
  FUNDDOCUMENTCODE    NUMBER not null,
  FUNDDOCUMENTNAMEENG VARCHAR2(100),
  FUNDDOCUMENTNAMEUNI VARCHAR2(100),
  FUNDCATEGOERYCODE   NUMBER
)
;
alter table nst_nasaka_FINANCE.FUNDDOCUMENTTYPE
  add constraint PKFUNDDOCUMENTCODE primary key (FUNDDOCUMENTCODE);
grant select on nst_nasaka_FINANCE.FUNDDOCUMENTTYPE to nst_nasaka_DB;

prompt
prompt Creating table PASSBOOKHEADER
prompt =============================
prompt
create table nst_nasaka_FINANCE.PASSBOOKHEADER
(
  TRANSACTIONNUMBER      NUMBER not null,
  BANKACCOUNTCODE        NUMBER,
  FROMDATE               DATE,
  TODATE                 DATE,
  PASSBOOKOPENINGBALANCE NUMBER,
  PASSBOOKCLOSINGBALANCE NUMBER
)
;
alter table nst_nasaka_FINANCE.PASSBOOKHEADER
  add constraint PKHEADER primary key (TRANSACTIONNUMBER);
alter table nst_nasaka_FINANCE.PASSBOOKHEADER
  add constraint UNFRDT unique (BANKACCOUNTCODE, FROMDATE);
grant select on nst_nasaka_FINANCE.PASSBOOKHEADER to nst_nasaka_DB;

prompt
prompt Creating table PASSBOOKDETAIL
prompt =============================
prompt
create table nst_nasaka_FINANCE.PASSBOOKDETAIL
(
  HEADERTRANSACTIONNUMBER NUMBER not null,
  SERIALNUMBER            NUMBER not null,
  BANKDATE                DATE not null,
  CHEQUEDDRTGSNO          NUMBER,
  CREDITAMOUNT            NUMBER,
  DEBITAMOUNT             NUMBER,
  CREDITBALANCE           NUMBER,
  DEBITBALANCE            NUMBER,
  NARRATION               VARCHAR2(200),
  FUNDDOCUMENTCODE        NUMBER
)
;
alter table nst_nasaka_FINANCE.PASSBOOKDETAIL
  add constraint PKPASSBOOK primary key (HEADERTRANSACTIONNUMBER, SERIALNUMBER);
alter table nst_nasaka_FINANCE.PASSBOOKDETAIL
  add constraint FKFUNDDOCUMENCODE foreign key (FUNDDOCUMENTCODE)
  references nst_nasaka_FINANCE.FUNDDOCUMENTTYPE (FUNDDOCUMENTCODE);
alter table nst_nasaka_FINANCE.PASSBOOKDETAIL
  add constraint FKHTRANSACTIONNUMBER foreign key (HEADERTRANSACTIONNUMBER)
  references nst_nasaka_FINANCE.PASSBOOKHEADER (TRANSACTIONNUMBER);
grant select on nst_nasaka_FINANCE.PASSBOOKDETAIL to nst_nasaka_DB;

prompt
prompt Creating table PERIODRESETCATEGORY
prompt ==================================
prompt
create table nst_nasaka_FINANCE.PERIODRESETCATEGORY
(
  PERIODRESETCATEGORYCODE NUMBER not null,
  PERIODRESETCATEGORYNAME VARCHAR2(100)
)
;
alter table nst_nasaka_FINANCE.PERIODRESETCATEGORY
  add constraint PKPERIODRESETCATEGORID primary key (PERIODRESETCATEGORYCODE);

prompt
prompt Creating table PROFITANDLOSS
prompt ============================
prompt
create table nst_nasaka_FINANCE.PROFITANDLOSS
(
  TRANSACTIONNUMBER NUMBER not null,
  FROMDATE          DATE,
  TODATE            DATE,
  OPPROFITCUR       NUMBER,
  OPLOSSCUR         NUMBER,
  PROFITCUR         NUMBER,
  LOSSCUR           NUMBER,
  CLPROFITCUR       NUMBER,
  CLLOSSCUR         NUMBER,
  OPPROFITPRE       NUMBER,
  OPLOSSPRE         NUMBER,
  PROFITPRE         NUMBER,
  LOSSPRE           NUMBER,
  CLPROFITPRE       NUMBER,
  CLLOSSPRE         NUMBER
)
;
alter table nst_nasaka_FINANCE.PROFITANDLOSS
  add constraint PKTRANSNO primary key (TRANSACTIONNUMBER);
alter table nst_nasaka_FINANCE.PROFITANDLOSS
  add constraint UKDTDT unique (FROMDATE, TODATE);

prompt
prompt Creating table RECONCILATIONDETAIL
prompt ==================================
prompt
create table nst_nasaka_FINANCE.RECONCILATIONDETAIL
(
  TRANSACTIONNUMBER         NUMBER not null,
  PASSBOOKTRANSACTIONNUMBER NUMBER not null,
  VOUCHERTRANSACTIONNUMBER  NUMBER,
  CHEQUEDDSERRIALNUMBER     NUMBER,
  PASSBOOKSERIALNUMBER      NUMBER,
  RECONCILIATIONREMARKCODE  NUMBER not null,
  CHEQUEDDNUMBER            NUMBER,
  AMOUNT                    NUMBER not null,
  FROMDATE                  DATE,
  TODATE                    DATE,
  ACCOUNTCODE               NUMBER not null
)
;
alter table nst_nasaka_FINANCE.RECONCILATIONDETAIL
  add constraint PKTRANNO_1 primary key (TRANSACTIONNUMBER);

prompt
prompt Creating table RECONCILATIONREMARKMASTER
prompt ========================================
prompt
create table nst_nasaka_FINANCE.RECONCILATIONREMARKMASTER
(
  RECONCILIATIONREMARKCODE NUMBER not null,
  REMARKENG                VARCHAR2(100),
  REMARKUNI                VARCHAR2(100)
)
;
alter table nst_nasaka_FINANCE.RECONCILATIONREMARKMASTER
  add constraint PKREM primary key (RECONCILIATIONREMARKCODE);

prompt
prompt Creating table SALEAUTOVOUCHERSETUP
prompt ===================================
prompt
create table nst_nasaka_FINANCE.SALEAUTOVOUCHERSETUP
(
  SALECATEGORY        NUMBER,
  SALESUBCATEGORY     NUMBER,
  SALEACCOUNTCODE     NUMBER,
  PURCHASERLEDGERCODE NUMBER,
  CGSTACCOUNTCODE     NUMBER,
  SGSTACCOUNTCODE     NUMBER,
  IGSTACCOUNTCODE     NUMBER,
  UGSTACCOUNTCODE     NUMBER,
  VATACCOUNTCODE      NUMBER
)
;

prompt
prompt Creating table STANDARDACCOUNT
prompt ==============================
prompt
create table nst_nasaka_FINANCE.STANDARDACCOUNT
(
  STANDARDACCOUNTCODE    NUMBER not null,
  STANDARDACCOUNTNAMEENG VARCHAR2(200) not null,
  STANDARDACCOUNTNAMEUNI VARCHAR2(200) not null
)
;
alter table nst_nasaka_FINANCE.STANDARDACCOUNT
  add constraint PKSTANDARDACCOUNTCODE primary key (STANDARDACCOUNTCODE);

prompt
prompt Creating table SUBLEDGERUPDATE
prompt ==============================
prompt
create table nst_nasaka_FINANCE.SUBLEDGERUPDATE
(
  SUBLEDGERTYPECODE NUMBER not null,
  CODE              NUMBER not null,
  NAMEENG           VARCHAR2(500),
  NAMEUNI           VARCHAR2(500) not null,
  ACTIVITY          NUMBER not null,
  ACTION            NUMBER
)
;

prompt
prompt Creating table SUBMAST
prompt ======================
prompt
create table nst_nasaka_FINANCE.SUBMAST
(
  CODE  VARCHAR2(10),
  NAME  VARCHAR2(500),
  ENAME VARCHAR2(500)
)
;

prompt
prompt Creating table TT
prompt =================
prompt
create table nst_nasaka_FINANCE.TT
(
  VOUCHERAPPROVALSTAGECODE NUMBER not null,
  STAGESERIALNUMBER        NUMBER not null,
  RESPONSIBILITYCODE       NUMBER not null,
  ISFINALSTAGE             NUMBER,
  ACTIONCODE               NUMBER
)
;

prompt
prompt Creating table VOUCHERACTIONDETAIL
prompt ==================================
prompt
create table nst_nasaka_FINANCE.VOUCHERACTIONDETAIL
(
  VOUCHERACTIONID   NUMBER not null,
  TRANSACTIONNUMBER NUMBER not null,
  USERCODE          NUMBER not null,
  ACTIONCODE        NUMBER not null,
  ACTIONDATE        DATE default sysdate not null
)
;
alter table nst_nasaka_FINANCE.VOUCHERACTIONDETAIL
  add constraint PKAPPRDETTRANSNUM primary key (VOUCHERACTIONID);
alter table nst_nasaka_FINANCE.VOUCHERACTIONDETAIL
  add constraint FKVOUCHERACTION foreign key (ACTIONCODE)
  references nst_nasaka_FINANCE.ACCOUNTVOUCHERACTION (ACTIONCODE);
grant select on nst_nasaka_FINANCE.VOUCHERACTIONDETAIL to nst_nasaka_DB;

prompt
prompt Creating table VOUCHERTYPE
prompt ==========================
prompt
create table nst_nasaka_FINANCE.VOUCHERTYPE
(
  VOUCHERTYPECODE    NUMBER not null,
  VOUCHERTYPENAMEENG VARCHAR2(100),
  VOUCHERTYPENAMEUNI VARCHAR2(100)
)
;
alter table nst_nasaka_FINANCE.VOUCHERTYPE
  add constraint PKVOUCHERTYPECODE primary key (VOUCHERTYPECODE);
grant select on nst_nasaka_FINANCE.VOUCHERTYPE to nst_nasaka_DB;

prompt
prompt Creating table VOUCHERNUMBERSERIES
prompt ==================================
prompt
create table nst_nasaka_FINANCE.VOUCHERNUMBERSERIES
(
  VOUCHERNUMBERSERIESID      NUMBER(19) not null,
  VOUCHERNUMBERSERIESNAMEENG VARCHAR2(1000) not null,
  VOUCHERNUMBERSERIESNAMEUNI VARCHAR2(1000) not null,
  VOUCHERNUMBERSTARTINGFROM  NUMBER(19) not null,
  PERIODRESETCATEGORYCODE    NUMBER(19) not null,
  VOUCHERNUMBERPREFIX        VARCHAR2(10) not null
)
;
alter table nst_nasaka_FINANCE.VOUCHERNUMBERSERIES
  add constraint PKVOUCHERNUMBERSERIESID primary key (VOUCHERNUMBERSERIESID);
alter table nst_nasaka_FINANCE.VOUCHERNUMBERSERIES
  add constraint FKPERIODRESETCAT foreign key (PERIODRESETCATEGORYCODE)
  references nst_nasaka_FINANCE.PERIODRESETCATEGORY (PERIODRESETCATEGORYCODE);
grant select on nst_nasaka_FINANCE.VOUCHERNUMBERSERIES to nst_nasaka_DB;

prompt
prompt Creating table VOUCHERSUBTYPE
prompt =============================
prompt
create table nst_nasaka_FINANCE.VOUCHERSUBTYPE
(
  VOUCHERSUBTYPECODE    NUMBER not null,
  VOUCHERSUBTYPENAMEENG VARCHAR2(200),
  VOUCHERSUBTYPENAMEUNI VARCHAR2(200),
  VOUCHERTYPECODE       NUMBER,
  VOUCHERNUMBERSERIESID NUMBER
)
;
alter table nst_nasaka_FINANCE.VOUCHERSUBTYPE
  add constraint PKVOUCHERSUBTYPECODE primary key (VOUCHERSUBTYPECODE);
alter table nst_nasaka_FINANCE.VOUCHERSUBTYPE
  add constraint FKVOUCHERNUMBERSERIES foreign key (VOUCHERNUMBERSERIESID)
  references nst_nasaka_FINANCE.VOUCHERNUMBERSERIES (VOUCHERNUMBERSERIESID);
alter table nst_nasaka_FINANCE.VOUCHERSUBTYPE
  add constraint FKVOUCHERTYPECODE foreign key (VOUCHERTYPECODE)
  references nst_nasaka_FINANCE.VOUCHERTYPE (VOUCHERTYPECODE);
grant select on nst_nasaka_FINANCE.VOUCHERSUBTYPE to nst_nasaka_DB;

prompt
prompt Creating table VOUCHERAPPROVALSTAGE
prompt ===================================
prompt
create table nst_nasaka_FINANCE.VOUCHERAPPROVALSTAGE
(
  VOUCHERAPPROVALSTAGECODE NUMBER not null,
  VOUCHERSUBTYPECODE       NUMBER not null
)
;
alter table nst_nasaka_FINANCE.VOUCHERAPPROVALSTAGE
  add constraint PKVOUAPPRSTAGE primary key (VOUCHERAPPROVALSTAGECODE);
alter table nst_nasaka_FINANCE.VOUCHERAPPROVALSTAGE
  add constraint UQVOUSUBTYPE unique (VOUCHERSUBTYPECODE);
alter table nst_nasaka_FINANCE.VOUCHERAPPROVALSTAGE
  add constraint FKVOUSUBTYPE foreign key (VOUCHERSUBTYPECODE)
  references nst_nasaka_FINANCE.VOUCHERSUBTYPE (VOUCHERSUBTYPECODE);

prompt
prompt Creating table VOUCHERAPPROVALSTAGEDETAIL
prompt =========================================
prompt
create table nst_nasaka_FINANCE.VOUCHERAPPROVALSTAGEDETAIL
(
  VOUCHERAPPROVALSTAGECODE NUMBER not null,
  STAGESERIALNUMBER        NUMBER not null,
  RESPONSIBILITYCODE       NUMBER not null,
  ISFINALSTAGE             NUMBER,
  ACTIONCODE               NUMBER
)
;
alter table nst_nasaka_FINANCE.VOUCHERAPPROVALSTAGEDETAIL
  add constraint PKVOUSTAGESER primary key (VOUCHERAPPROVALSTAGECODE, STAGESERIALNUMBER);
alter table nst_nasaka_FINANCE.VOUCHERAPPROVALSTAGEDETAIL
  add constraint FKACTIONCODE foreign key (ACTIONCODE)
  references nst_nasaka_FINANCE.ACCOUNTVOUCHERACTION (ACTIONCODE);
alter table nst_nasaka_FINANCE.VOUCHERAPPROVALSTAGEDETAIL
  add constraint FKVOUAPPRSTAGCD foreign key (VOUCHERAPPROVALSTAGECODE)
  references nst_nasaka_FINANCE.VOUCHERAPPROVALSTAGE (VOUCHERAPPROVALSTAGECODE);

prompt
prompt Creating table VOUCHERHEADER
prompt ============================
prompt
create table nst_nasaka_FINANCE.VOUCHERHEADER
(
  TRANSACTIONNUMBER        NUMBER not null,
  LEGALENTITYCODE          NUMBER not null,
  YEARPERIODCODE           NUMBER not null,
  VOUCHERSUBTYPECODE       NUMBER not null,
  VOUCHERNUMBERBASEVALUE   VARCHAR2(15),
  VOUCHERNUMBER            NUMBER,
  VOUCHERNUMBERPREFIXSUFIX VARCHAR2(50),
  VOUCHERDATE              DATE not null,
  DESCRIPTION              VARCHAR2(300),
  NARRATION                VARCHAR2(2000),
  APPROVALSTATUS           NUMBER,
  VOUCHERNUMBERSERIESID    NUMBER not null,
  APPLICATIONNUMBER        NUMBER,
  TOTALDEBIT               NUMBER,
  TOTALCREDIT              NUMBER,
  DISAPPROVALREASON        VARCHAR2(500)
)
;
alter table nst_nasaka_FINANCE.VOUCHERHEADER
  add constraint PKTRANSACTIONNUMBER primary key (TRANSACTIONNUMBER);
alter table nst_nasaka_FINANCE.VOUCHERHEADER
  add constraint UNQ_BASEVOUNUM unique (VOUCHERNUMBERBASEVALUE, VOUCHERNUMBERPREFIXSUFIX);
alter table nst_nasaka_FINANCE.VOUCHERHEADER
  add constraint FKVOUCHERNUMBERSERIESID foreign key (VOUCHERNUMBERSERIESID)
  references nst_nasaka_FINANCE.VOUCHERNUMBERSERIES (VOUCHERNUMBERSERIESID);
create index nst_nasaka_FINANCE.TRNYRAPP on nst_nasaka_FINANCE.VOUCHERHEADER (TRANSACTIONNUMBER, YEARPERIODCODE, APPROVALSTATUS, VOUCHERDATE);
grant select on nst_nasaka_FINANCE.VOUCHERHEADER to nst_nasaka_DB;
grant delete, references on nst_nasaka_FINANCE.VOUCHERHEADER to nst_nasaka_SALE;

prompt
prompt Creating table VOUCHERCHEQUEDDDETAIL
prompt ====================================
prompt
create table nst_nasaka_FINANCE.VOUCHERCHEQUEDDDETAIL
(
  TRANSACTIONNUMBER     NUMBER not null,
  BANKACCOUNTCODE       NUMBER not null,
  FUNDDOCUMENTCODE      NUMBER not null,
  FUNDDOCUMENTDATE      DATE,
  FUNDDOCUMENTNUMBER    NUMBER,
  FUNDDOCUMENTAMOUNT    NUMBER not null,
  DRAWEEBANK            VARCHAR2(200),
  OPPOSITEACCOUNTCODE   NUMBER,
  OPPOSITESUBLEDGERCODE NUMBER,
  DETAILSERIALNUMBER    NUMBER not null
)
;
alter table nst_nasaka_FINANCE.VOUCHERCHEQUEDDDETAIL
  add constraint PKCHDD primary key (TRANSACTIONNUMBER, DETAILSERIALNUMBER);
alter table nst_nasaka_FINANCE.VOUCHERCHEQUEDDDETAIL
  add constraint VOUCHERHEAD1 foreign key (TRANSACTIONNUMBER)
  references nst_nasaka_FINANCE.VOUCHERHEADER (TRANSACTIONNUMBER) on delete cascade;
grant select on nst_nasaka_FINANCE.VOUCHERCHEQUEDDDETAIL to nst_nasaka_DB;

prompt
prompt Creating table VOUCHERDETAIL
prompt ============================
prompt
create table nst_nasaka_FINANCE.VOUCHERDETAIL
(
  TRANSACTIONNUMBER  NUMBER not null,
  DETAILSERIALNUMBER NUMBER not null,
  ACCOUNTCODE        NUMBER not null,
  SUBLEDGERCODE      NUMBER,
  CREDIT             NUMBER,
  DEBIT              NUMBER,
  COSTCENTRECODE     NUMBER
)
;
alter table nst_nasaka_FINANCE.VOUCHERDETAIL
  add constraint PKTRNSERNO primary key (TRANSACTIONNUMBER, DETAILSERIALNUMBER);
alter table nst_nasaka_FINANCE.VOUCHERDETAIL
  add constraint FKCOSTCENTRECODE foreign key (COSTCENTRECODE)
  references nst_nasaka_FINANCE.COSTCENTREHEAD (COSTCENTRECODE);
alter table nst_nasaka_FINANCE.VOUCHERDETAIL
  add constraint FKVOUHEAD foreign key (TRANSACTIONNUMBER)
  references nst_nasaka_FINANCE.VOUCHERHEADER (TRANSACTIONNUMBER) on delete cascade;
create index nst_nasaka_FINANCE.TRNAC on nst_nasaka_FINANCE.VOUCHERDETAIL (TRANSACTIONNUMBER, ACCOUNTCODE, CREDIT, DEBIT);
grant select on nst_nasaka_FINANCE.VOUCHERDETAIL to nst_nasaka_DB;

prompt
prompt Creating table VOUCHERNOTEDETAILS
prompt =================================
prompt
create table nst_nasaka_FINANCE.VOUCHERNOTEDETAILS
(
  TRANSACTIONNUMBER        NUMBER not null,
  VOUCHERTRANSACTIONNUMBER NUMBER,
  NOTECODE                 NUMBER,
  CREDITNOTESANKYA_        NUMBER,
  DEBITNOTESANKYA          NUMBER
)
;
alter table nst_nasaka_FINANCE.VOUCHERNOTEDETAILS
  add constraint PKTANNO primary key (TRANSACTIONNUMBER);
alter table nst_nasaka_FINANCE.VOUCHERNOTEDETAILS
  add constraint FKNOTRCHALAN foreign key (NOTECODE)
  references nst_nasaka_FINANCE.ACCOUNTNOTEMASTER (NOTECODE);

prompt
prompt Creating view VW_ACCOUNT_ALL_TRANSACTION
prompt ========================================
prompt
create or replace view nst_nasaka_finance.vw_account_all_transaction as
select t.yearperiodcode,t.entitycode legalentitycode,to_date('31-mar-'||substr(yearperiodcode,1,4),'dd-MM-yyyy') cdate ,t.accountcode, t.subledgercode, nvl(t.debitbalance,0) debit,nvl(t.creditbalance,0) credit from accountopening t
union all
select  h.yearperiodcode ,h.legalentitycode,h.voucherdate cdate, d.accountcode,d.subledgercode ,nvl(d.debit,0)debit,nvl(d.credit,0)credit
from voucherheader h,voucherdetail d
where h.transactionnumber=d.transactionnumber and h.approvalstatus=9;

prompt
prompt Creating view VW_DAYBOOK_CREDIT_ACCOUNT_SUM
prompt ===========================================
prompt
create or replace view nst_nasaka_finance.vw_daybook_credit_account_sum as
select t.yearperiodcode,t.voucherdate,t.accountcode,h.accountnameeng,h.accountnameuni
,sum(case when t.cashbank=1 then t.credit else 0 end) cash
,sum(case when t.cashbank=2 then t.credit else 0 end) bank
,sum(t.credit) total
from
(
select t.yearperiodcode,t.voucherdate,d.accountcode
,case when s.vouchersubtypecode in (1,8) then 1 else 2 end cashbank
,nvl(d.credit,0) as credit
from voucherheader t,voucherdetail d,vouchersubtype s,accountcontroltable c
where t.transactionnumber=d.transactionnumber
and t.vouchersubtypecode=s.vouchersubtypecode
and s.vouchertypecode in (1,2,3)
and d.accountcode<>c.cashaccountcode
and d.credit>0)t,accounthead h
where t.accountcode=h.accountcode
group by t.yearperiodcode,t.voucherdate,t.accountcode,h.accountnameeng,h.accountnameuni;

prompt
prompt Creating view VW_DAYBOOK_CREDIT_DETAIL
prompt ======================================
prompt
create or replace view nst_nasaka_finance.vw_daybook_credit_detail as
select voucherdate,vouchersubtypecode,transactionnumber,vouchernumberprefixsufix,accountcode
,subledgercode,subledgernameuni,description
,sum(case when cashbank=1 then credit else 0 end) cash
,sum(case when cashbank=2 then credit else 0 end) bank
,sum(credit) total
from (
select t.voucherdate,h.vouchersubtypecode,h.transactionnumber,h.vouchernumberprefixsufix,d.accountcode,
d.subledgercode,s.subledgernameuni,h.description,
case when p.vouchersubtypecode in (1,8) then 1 else 2 end cashbank,d.credit
from vw_daybook_credit_account_sum t,voucherheader h
,voucherdetail d,accounthead a
,accountcontroltable b,vouchersubtype p
,accountsubledger s
where t.voucherdate=h.voucherdate
and t.accountcode=a.accountcode
and d.accountcode=a.accountcode
and h.transactionnumber=d.transactionnumber
and d.credit>0
and p.vouchertypecode in (1,2,3)
and a.accountcode<>b.cashaccountcode
and h.vouchersubtypecode=p.vouchersubtypecode
and d.accountcode=s.accountcode(+)
and d.subledgercode=s.subledgercode(+))
group by voucherdate,vouchersubtypecode,transactionnumber,vouchernumberprefixsufix,accountcode,subledgercode,subledgernameuni,description;

prompt
prompt Creating view VW_DAYBOOK_DEBIT_ACCOUNT_SUM
prompt ==========================================
prompt
create or replace view nst_nasaka_finance.vw_daybook_debit_account_sum as
select t.yearperiodcode,t.voucherdate,t.accountcode,h.accountnameeng,h.accountnameuni
,sum(case when cashbank=1 then debit else 0 end) cash
,sum(case when cashbank=2 then debit else 0 end) bank
,sum(debit) total
from
(
select t.yearperiodcode,t.voucherdate,d.accountcode
,case when s.vouchersubtypecode in (4,7) then 1 else 2 end cashbank
,nvl(d.debit,0) as debit
from voucherheader t,voucherdetail d,vouchersubtype s,accountcontroltable c
where t.transactionnumber=d.transactionnumber
and t.vouchersubtypecode=s.vouchersubtypecode
and s.vouchertypecode in (1,2,3)
and d.accountcode<>c.cashaccountcode
and d.debit>0)t,accounthead h
where t.accountcode=h.accountcode
group by t.yearperiodcode,t.voucherdate,t.accountcode,h.accountnameeng,h.accountnameuni;

prompt
prompt Creating view VW_DAYBOOK_DEBIT_DETAIL
prompt =====================================
prompt
create or replace view nst_nasaka_finance.vw_daybook_debit_detail as
select voucherdate,vouchersubtypecode,transactionnumber
,vouchernumberprefixsufix,accountcode
,subledgercode,subledgernameuni,description
,sum(case when cashbank=1 then debit else 0 end) cash
,sum(case when cashbank=2 then debit else 0 end) bank
,sum(debit) total
from (
select t.voucherdate,h.vouchersubtypecode,h.transactionnumber
,h.vouchernumberprefixsufix,d.accountcode
,d.subledgercode,s.subledgernameuni,h.description,
case when p.vouchersubtypecode in (4,7) then 1 else 2 end cashbank,d.debit
from vw_daybook_debit_account_sum t,voucherheader h
,voucherdetail d,accounthead a
,accountcontroltable b,vouchersubtype p
,accountsubledger s
where t.voucherdate=h.voucherdate
and t.accountcode=a.accountcode
and d.accountcode=a.accountcode
and h.transactionnumber=d.transactionnumber
and d.debit>0
and p.vouchertypecode in (1,2,3)
and a.accountcode<>b.cashaccountcode
and h.vouchersubtypecode=p.vouchersubtypecode
and d.accountcode=s.accountcode(+)
and d.subledgercode=s.subledgercode(+))
group by voucherdate,vouchersubtypecode,transactionnumber
,vouchernumberprefixsufix,accountcode
,subledgercode,subledgernameuni,description;

prompt
prompt Creating view VW_GROUP_MISMACH_CHECK
prompt ====================================
prompt
create or replace view nst_nasaka_finance.vw_group_mismach_check as
select groupcode,subgroupcode,sum(y) as y,sum(n) as n
from (
select groupcode,subgroupcode,
case when subsubgroupcode is not null then 1 else 0 end y,
case when subsubgroupcode is null then 1 else 0 end n
from(
select h.groupcode,h.subgroupcode,h.subsubgroupcode,count(*)
from accounthead h
group by h.groupcode,h.subgroupcode,h.subsubgroupcode
order by h.groupcode,h.subgroupcode,h.subsubgroupcode))
group by groupcode,subgroupcode
having sum(y)>0 and sum(n)>0;

prompt
prompt Creating view VW_SCHEDULE_MISMACH_CHECK_1
prompt =========================================
prompt
create or replace view nst_nasaka_finance.vw_schedule_mismach_check_1 as
select schedulecode,subschedulecode,sum(y) as y,sum(n) as n
from (
select schedulecode,subschedulecode,
case when subsubschedulecode is not null then 1 else 0 end y,
case when subsubschedulecode is null then 1 else 0 end n
from(
select h.schedulecode,h.subschedulecode,h.subsubschedulecode,count(*)
from accounthead h
group by h.schedulecode,h.subschedulecode,h.subsubschedulecode
order by h.schedulecode,h.subschedulecode,h.subsubschedulecode))
group by schedulecode,subschedulecode
having sum(y)>0 and sum(n)>0;

prompt
prompt Creating view VW_VOUCHER_APPROVAL_PENDING
prompt =========================================
prompt
create or replace view nst_nasaka_finance.vw_voucher_approval_pending as
select t.transactionnumber,t.vouchernumberprefixsufix,t.voucherdate,
t.totaldebit,d.responsibilitycode,d.actioncode,p.vouchersubtypenameeng,
p.vouchersubtypenameuni,d.stageserialnumber
 from voucherheader t ,
voucherapprovalstage s,
voucherapprovalstagedetail d,
vouchersubtype p
where t.vouchersubtypecode=s.vouchersubtypecode
and s.voucherapprovalstagecode=d.voucherapprovalstagecode
and t.approvalstatus+1=d.stageserialnumber
and nvl(t.vouchernumber,0)>0
and t.vouchersubtypecode=p.vouchersubtypecode;

prompt
prompt Creating function ACCOUNTCLOSINGBALANCE
prompt =======================================
prompt
create or replace function nst_nasaka_finance.accountclosingbalance(p_yearcode in number,p_accountcode in number,p_date in date) return number is
begin
declare
m_balance number;
begin
      select nvl(sum(debitbalance),0)-nvl(sum(creditbalance),0) into m_balance
      from (
      select nvl(a.creditbalance,0) as creditbalance,nvl(a.debitbalance,0) as debitbalance 
      from accountopening a
      where a.yearperiodcode=p_yearcode and a.accountcode=p_accountcode
      union all
      select nvl(sum(d.credit),0) as creditbalance,nvl(sum(d.debit),0) as debitbalance 
      from voucherheader t,voucherdetail d
      where t.transactionnumber=d.transactionnumber 
      and t.yearperiodcode=p_yearcode and t.voucherdate<=p_date
      and d.accountcode=p_accountcode and t.approvalstatus=9);
      return m_balance;
end;
end accountclosingbalance;
/

prompt
prompt Creating function ACCOUNTCREDIT
prompt ===============================
prompt
create or replace function nst_nasaka_finance.accountcredit(p_yearcode in number,p_accountcode in number,p_fromdate in date,p_todate in date) return number is
begin
declare
m_balance number;
begin
      select nvl(sum(creditbalance),0) into m_balance
      from (
      select nvl(sum(d.credit),0) as creditbalance
      from voucherheader t,voucherdetail d
      where t.transactionnumber=d.transactionnumber 
      and t.yearperiodcode=p_yearcode and t.voucherdate>=p_fromdate and t.voucherdate<=p_todate
      and d.accountcode=p_accountcode and t.approvalstatus=9 and nvl(d.credit,0)>0);
      return m_balance;
end;
end accountcredit;
/

prompt
prompt Creating function ACCOUNTDEBIT
prompt ==============================
prompt
create or replace function nst_nasaka_finance.accountdebit(p_yearcode in number,p_accountcode in number,p_fromdate in date,p_todate in date) return number is
begin
declare
m_balance number;
begin
      select nvl(sum(debitbalance),0) into m_balance
      from (
      select nvl(sum(d.debit),0) as debitbalance
      from voucherheader t,voucherdetail d
      where t.transactionnumber=d.transactionnumber 
      and t.yearperiodcode=p_yearcode and t.voucherdate>=p_fromdate and t.voucherdate<=p_todate
      and d.accountcode=p_accountcode and t.approvalstatus=9 and nvl(d.debit,0)>0);
      return m_balance;
end;
end accountdebit;
/

prompt
prompt Creating function ACCOUNTOPENINGBALANCE
prompt =======================================
prompt
create or replace function nst_nasaka_finance.accountopeningbalance(p_yearcode in number,p_accountcode in number,p_date in date) return number is
begin
declare
m_balance number;
begin
      select nvl(sum(debitbalance),0)-nvl(sum(creditbalance),0) into m_balance
      from (
      select nvl(a.creditbalance,0) as creditbalance,nvl(a.debitbalance,0) as debitbalance 
      from accountopening a
      where a.yearperiodcode=p_yearcode and a.accountcode=p_accountcode
      union all
      select nvl(sum(d.credit),0) as creditbalance,nvl(sum(d.debit),0) as debitbalance 
      from voucherheader t,voucherdetail d
      where t.transactionnumber=d.transactionnumber 
      and t.yearperiodcode=p_yearcode and t.voucherdate<p_date
      and d.accountcode=p_accountcode and t.approvalstatus=9);
      return m_balance;
end;
end accountopeningbalance;
/

prompt
prompt Creating function GROUPCLOSINGBALANCE
prompt =====================================
prompt
create or replace function nst_nasaka_finance.groupclosingbalance(p_yearcode in number,p_groupcode in number,p_date in date) return number is
begin
declare
m_balance number;
begin
      select nvl(sum(debitbalance),0)-nvl(sum(creditbalance),0) into m_balance
      from (
      select nvl(a.creditbalance,0) as creditbalance,nvl(a.debitbalance,0) as debitbalance 
      from accountopening a,accounthead aa
      where a.accountcode= aa.accountcode
       and a.yearperiodcode=p_yearcode 
       and aa.groupcode=p_groupcode
       and a.yearperiodcode=p_yearcode
      union all
      select nvl(sum(d.credit),0) as creditbalance,nvl(sum(d.debit),0) as debitbalance 
      from voucherheader t,voucherdetail d,accounthead aa
      where t.transactionnumber=d.transactionnumber 
      and d.accountcode= aa.accountcode
       and t.yearperiodcode=p_yearcode 
       and aa.groupcode=p_groupcode
       and t.yearperiodcode=p_yearcode
      and t.yearperiodcode=p_yearcode and t.voucherdate<=p_date
      and t.approvalstatus=9);
      return nvl(m_balance,0);
end;
end groupclosingbalance;
/

prompt
prompt Creating function GROUPOPENINGBALANCE
prompt =====================================
prompt
create or replace function nst_nasaka_finance.groupopeningbalance(p_yearcode in number,p_groupcode in number,p_date in date) return number is
begin
declare
m_balance number;
begin
      select nvl(sum(debitbalance),0)-nvl(sum(creditbalance),0) into m_balance
      from (
      select nvl(a.creditbalance,0) as creditbalance,nvl(a.debitbalance,0) as debitbalance 
      from accountopening a,accounthead aa
      where a.accountcode= aa.accountcode
       and a.yearperiodcode=p_yearcode 
       and aa.groupcode=p_groupcode
       and a.yearperiodcode=p_yearcode
      union all
      select nvl(sum(d.credit),0) as creditbalance,nvl(sum(d.debit),0) as debitbalance 
      from voucherheader t,voucherdetail d,accounthead aa
      where t.transactionnumber=d.transactionnumber 
      and d.accountcode= aa.accountcode
       and t.yearperiodcode=p_yearcode 
       and aa.groupcode=p_groupcode
       and t.yearperiodcode=p_yearcode
      and t.yearperiodcode=p_yearcode and t.voucherdate<p_date
      and t.approvalstatus=9);
      return nvl(m_balance,0);
end;
end groupopeningbalance;
/

prompt
prompt Creating function OPPACCOUNTHEAD
prompt ================================
prompt
create or replace function nst_nasaka_finance.oppaccounthead(p_transactionnumber in number,p_accountcode in number,p_subledgercode in number default null) return varchar2 is
begin
declare
m_balance number;
mcrdr varchar2(2);
maccountnameuni varchar2(300);
maccountnameuni1 varchar2(300);
msubledgernameuni varchar2(300);
mcnt number;
cursor c1 is
   select case 
          when nvl(d.credit,0)>0 then 'Cr' else 'Dr' end crdr
      from voucherheader t,voucherdetail d
      where t.transactionnumber=d.transactionnumber 
      and t.transactionnumber=p_transactionnumber
      and d.accountcode=p_accountcode
      and nvl(d.subledgercode,0)=nvl(p_subledgercode,0);
cursor c2 is
   select a.accountnameuni,nvl(s.subledgernameuni,' ') as subledgernameuni
      from voucherheader t,voucherdetail d,accounthead a,accountsubledger s
      where t.transactionnumber=d.transactionnumber 
      and t.transactionnumber=p_transactionnumber
      and d.accountcode=a.accountcode
      and d.accountcode=s.accountcode(+)
      and d.subledgercode=s.subledgercode(+)
      and nvl(d.debit,0)>0
      order by nvl(d.debit,0) desc;
cursor c3 is
   select a.accountnameuni,nvl(s.subledgernameuni,' ') as subledgernameuni
      from voucherheader t,voucherdetail d,accounthead a,accountsubledger s
      where t.transactionnumber=d.transactionnumber 
      and t.transactionnumber=p_transactionnumber
      and d.accountcode=a.accountcode
      and d.accountcode=s.accountcode(+)
      and d.subledgercode=s.subledgercode(+)
      and nvl(d.credit,0)>0
      order by nvl(d.credit,0) desc;
begin
   open c1;
   fetch c1 into mcrdr;
   close c1;
   if mcrdr='Cr' then
     open c2;
     mcnt:=0;
     loop
     fetch c2 into maccountnameuni,msubledgernameuni;
     if c2%notfound then
         exit;
     else if mcnt=0 then
          if (msubledgernameuni=' ') then
             maccountnameuni1:=maccountnameuni;
          else
             maccountnameuni1:=maccountnameuni||' -'||msubledgernameuni;
          end if;
     else if mcnt=1 then
          if (msubledgernameuni=' ') then
              maccountnameuni1:=maccountnameuni1||' आणि इतर';
          else
              maccountnameuni1:=maccountnameuni1||' -'||msubledgernameuni||' आणि इतर';
          end if;
          exit;
     end if;
     end if;
     end if;
         mcnt:=mcnt+1;
     end loop;     
     close c2;
   else if mcrdr='Dr' then
     open c3;
     mcnt:=0;
     loop
     fetch c3 into maccountnameuni,msubledgernameuni;
     if c3%notfound then
         exit;
     else if mcnt=0 then
          if (msubledgernameuni=' ') then
             maccountnameuni1:=maccountnameuni;
          else
             maccountnameuni1:=maccountnameuni||' -'||msubledgernameuni;
          end if;
     else if mcnt=1 then
          if (msubledgernameuni=' ') then
              maccountnameuni1:=maccountnameuni1||' आणि इतर';
          else
              maccountnameuni1:=maccountnameuni1||' -'||msubledgernameuni||' आणि इतर';
          end if;
          exit;
     end if;
     end if;
     end if;
         mcnt:=mcnt+1;
     end loop;     
     close c3; 
   end if;
   end if;
   return maccountnameuni1;
end;
end oppaccounthead;
/

prompt
prompt Creating function SCHEDULECLOSINGBALANCE
prompt ========================================
prompt
create or replace function nst_nasaka_finance.scheduleclosingbalance(p_yearcode in number,p_schedulecode in number,p_date in date) return number is
begin
declare
m_balance number;
begin
      select nvl(sum(debitbalance),0)-nvl(sum(creditbalance),0) into m_balance
      from (
      select nvl(a.creditbalance,0) as creditbalance,nvl(a.debitbalance,0) as debitbalance 
      from accountopening a,accounthead aa,accountschedule s
      where a.accountcode= aa.accountcode
       and aa.schedulecode=s.schedulecode
       and a.yearperiodcode=p_yearcode 
       and aa.schedulecode=p_schedulecode
       and a.yearperiodcode=p_yearcode
      union all
      select nvl(sum(d.credit),0) as creditbalance,nvl(sum(d.debit),0) as debitbalance 
      from voucherheader t,voucherdetail d,accounthead aa,accountschedule s
      where t.transactionnumber=d.transactionnumber 
      and d.accountcode= aa.accountcode
       and aa.schedulecode=s.schedulecode
       and t.yearperiodcode=p_yearcode 
       and aa.schedulecode=p_schedulecode
       and t.yearperiodcode=p_yearcode
      and t.yearperiodcode=p_yearcode and t.voucherdate<=p_date
      and s.schedulecode=p_schedulecode and t.approvalstatus=9);
      return nvl(m_balance,0);
end;
end scheduleclosingbalance;
/

prompt
prompt Creating function SCHEDULECREDIT
prompt ================================
prompt
create or replace function nst_nasaka_finance.schedulecredit(p_yearcode in number,p_schedulecode in number,p_fromdate in date,p_todate in date) return number is
begin
declare
m_balance number;
begin
      select nvl(sum(creditbalance),0) into m_balance
      from (
       select nvl(sum(d.credit),0) as creditbalance 
      from voucherheader t,voucherdetail d,accounthead aa,accountschedule s
      where t.transactionnumber=d.transactionnumber 
      and d.accountcode= aa.accountcode
       and aa.schedulecode=s.schedulecode
       and t.yearperiodcode=p_yearcode 
       and aa.schedulecode=p_schedulecode
       and t.yearperiodcode=p_yearcode
      and t.yearperiodcode=p_yearcode 
      and t.voucherdate>=p_fromdate and t.voucherdate<=p_todate
      and nvl(d.credit,0)>0
      and s.schedulecode=p_schedulecode and t.approvalstatus=9);
      return m_balance;
end;
end schedulecredit;
/

prompt
prompt Creating function SCHEDULEDEBIT
prompt ===============================
prompt
create or replace function nst_nasaka_finance.scheduledebit(p_yearcode in number,p_schedulecode in number,p_fromdate in date,p_todate in date) return number is
begin
declare
m_balance number;
begin
      select nvl(sum(debitbalance),0) into m_balance
      from (
       select nvl(sum(d.debit),0) as debitbalance 
      from voucherheader t,voucherdetail d,accounthead aa,accountschedule s
      where t.transactionnumber=d.transactionnumber 
      and d.accountcode= aa.accountcode
       and aa.schedulecode=s.schedulecode
       and t.yearperiodcode=p_yearcode 
       and aa.schedulecode=p_schedulecode
       and t.yearperiodcode=p_yearcode
      and t.yearperiodcode=p_yearcode 
      and t.voucherdate>=p_fromdate and t.voucherdate<=p_todate
      and nvl(d.debit,0)>0
      and s.schedulecode=p_schedulecode and t.approvalstatus=9);
      return m_balance;
end;
end scheduledebit;
/

prompt
prompt Creating function SCHEDULENUMBER
prompt ================================
prompt
create or replace function nst_nasaka_finance.schedulenumber(p_groupcode in number, p_subgroupcode in number default null,p_subsubgroupcode in number default null) return number is
begin
declare
mschedulenumber number;
cursor scheduleno is
select s.schedulenumber from accountschedule s
where s.groupcode=p_groupcode
and nvl(s.subgroupcode,0)=nvl(p_subgroupcode,0)
and nvl(s.subsubgroupcode,0)=nvl(p_subsubgroupcode,0);
begin
   open scheduleno;
   fetch scheduleno into mschedulenumber;
   close scheduleno;
   return mschedulenumber;
end;
end schedulenumber;
/

prompt
prompt Creating function SUBACCOUNTCLOSINGBALANCE
prompt ==========================================
prompt
create or replace function nst_nasaka_finance.subaccountclosingbalance(p_yearcode in number,p_accountcode in number,p_subledgercode in number,p_date in date) return number is
begin
declare
m_balance number;
begin
      select nvl(sum(debitbalance),0)-nvl(sum(creditbalance),0) into m_balance
      from (
      select nvl(a.creditbalance,0) as creditbalance,nvl(a.debitbalance,0) as debitbalance 
      from accountopening a
      where a.yearperiodcode=p_yearcode and a.accountcode=p_accountcode and a.subledgercode=p_subledgercode
      union all
      select nvl(sum(d.credit),0) as creditbalance,nvl(sum(d.debit),0) as debitbalance 
      from voucherheader t,voucherdetail d
      where t.transactionnumber=d.transactionnumber 
      and t.yearperiodcode=p_yearcode and t.voucherdate<=p_date
      and d.accountcode=p_accountcode and d.subledgercode=p_subledgercode
      and t.approvalstatus=9);
      return m_balance;
end;
end subaccountclosingbalance;
/

prompt
prompt Creating function SUBACCOUNTCREDIT
prompt ==================================
prompt
create or replace function nst_nasaka_finance.subaccountcredit(p_yearcode in number,p_accountcode in number,p_subledgercode in number,p_fromdate in date,p_todate in date) return number is
begin
declare
m_balance number;
begin
      select nvl(sum(creditbalance),0) into m_balance
      from (
      select nvl(sum(d.credit),0) as creditbalance
      from voucherheader t,voucherdetail d
      where t.transactionnumber=d.transactionnumber 
      and t.yearperiodcode=p_yearcode and t.voucherdate>=p_fromdate and t.voucherdate<=p_todate
      and d.accountcode=p_accountcode and d.subledgercode=p_subledgercode and t.approvalstatus=9 and nvl(d.credit,0)>0);
      return m_balance;
end;
end subaccountcredit;
/

prompt
prompt Creating function SUBACCOUNTDEBIT
prompt =================================
prompt
create or replace function nst_nasaka_finance.subaccountdebit(p_yearcode in number,p_accountcode in number,p_subledgercode in number,p_fromdate in date,p_todate in date) return number is
begin
declare
m_balance number;
begin
      select nvl(sum(debitbalance),0) into m_balance
      from (
      select nvl(sum(d.debit),0) as debitbalance
      from voucherheader t,voucherdetail d
      where t.transactionnumber=d.transactionnumber 
      and t.yearperiodcode=p_yearcode and t.voucherdate>=p_fromdate and t.voucherdate<=p_todate
      and d.accountcode=p_accountcode and d.subledgercode=p_subledgercode and t.approvalstatus=9 and nvl(d.debit,0)>0);
      return m_balance;
end;
end subaccountdebit;
/

prompt
prompt Creating function SUBACCOUNTOPENINGBALANCE
prompt ==========================================
prompt
create or replace function nst_nasaka_finance.subaccountopeningbalance(p_yearcode in number,p_accountcode in number,p_subledgercode in number,p_date in date) return number is
begin
declare
m_balance number;
begin
      select nvl(sum(debitbalance),0)-nvl(sum(creditbalance),0) into m_balance
      from (
      select nvl(a.creditbalance,0) as creditbalance,nvl(a.debitbalance,0) as debitbalance 
      from accountopening a
      where a.yearperiodcode=p_yearcode and a.accountcode=p_accountcode and a.subledgercode=p_subledgercode
      union all
      select nvl(sum(d.credit),0) as creditbalance,nvl(sum(d.debit),0) as debitbalance 
      from voucherheader t,voucherdetail d
      where t.transactionnumber=d.transactionnumber 
      and t.yearperiodcode=p_yearcode and t.voucherdate<p_date
      and d.accountcode=p_accountcode and d.subledgercode=p_subledgercode
      and t.approvalstatus=9);
      return m_balance;
end;
end subaccountopeningbalance;
/

prompt
prompt Creating function SUBGROUPCLOSINGBALANCE
prompt ========================================
prompt
create or replace function nst_nasaka_finance.subgroupclosingbalance(p_yearcode in number,p_subgroupcode in number,p_date in date) return number is
begin
declare
m_balance number;
begin
      select nvl(sum(debitbalance),0)-nvl(sum(creditbalance),0) into m_balance
      from (
      select nvl(a.creditbalance,0) as creditbalance,nvl(a.debitbalance,0) as debitbalance 
      from accountopening a,accounthead aa
      where a.accountcode= aa.accountcode
       and a.yearperiodcode=p_yearcode 
       and aa.subgroupcode=p_subgroupcode
       and a.yearperiodcode=p_yearcode
      union all
      select nvl(sum(d.credit),0) as creditbalance,nvl(sum(d.debit),0) as debitbalance 
      from voucherheader t,voucherdetail d,accounthead aa
      where t.transactionnumber=d.transactionnumber 
      and d.accountcode= aa.accountcode
       and t.yearperiodcode=p_yearcode 
       and aa.subgroupcode=p_subgroupcode
       and t.yearperiodcode=p_yearcode
      and t.yearperiodcode=p_yearcode and t.voucherdate<=p_date
      and t.approvalstatus=9);
      return nvl(m_balance,0);
end;
end subgroupclosingbalance;
/

prompt
prompt Creating function SUBGROUPOPENINGBALANCE
prompt ========================================
prompt
create or replace function nst_nasaka_finance.subgroupopeningbalance(p_yearcode in number,p_subgroupcode in number,p_date in date) return number is
begin
declare
m_balance number;
begin
      select nvl(sum(debitbalance),0)-nvl(sum(creditbalance),0) into m_balance
      from (
      select nvl(a.creditbalance,0) as creditbalance,nvl(a.debitbalance,0) as debitbalance 
      from accountopening a,accounthead aa
      where a.accountcode= aa.accountcode
       and a.yearperiodcode=p_yearcode 
       and aa.subgroupcode=p_subgroupcode
       and a.yearperiodcode=p_yearcode
      union all
      select nvl(sum(d.credit),0) as creditbalance,nvl(sum(d.debit),0) as debitbalance 
      from voucherheader t,voucherdetail d,accounthead aa
      where t.transactionnumber=d.transactionnumber 
      and d.accountcode= aa.accountcode
       and t.yearperiodcode=p_yearcode 
       and aa.subgroupcode=p_subgroupcode
       and t.yearperiodcode=p_yearcode
      and t.yearperiodcode=p_yearcode and t.voucherdate<p_date
      and t.approvalstatus=9);
      return nvl(m_balance,0);
end;
end subgroupopeningbalance;
/

prompt
prompt Creating function SUBLEDGEROPENINGBALANCE
prompt =========================================
prompt
create or replace function nst_nasaka_finance.subledgeropeningbalance(p_yearcode in number,p_accountcode in number,p_subledgercode in number,p_date in date) return number is
begin
declare
m_balance number;
begin
      select nvl(sum(debitbalance),0)-nvl(sum(creditbalance),0) into m_balance
      from (
      select nvl(a.creditbalance,0) as creditbalance,nvl(a.debitbalance,0) as debitbalance 
      from accountopening a
      where a.yearperiodcode=p_yearcode 
      and a.accountcode=p_accountcode
      and a.subledgercode=p_subledgercode
      union all
      select nvl(sum(d.credit),0) as creditbalance,nvl(sum(d.debit),0) as debitbalance 
      from voucherheader t,voucherdetail d
      where t.transactionnumber=d.transactionnumber 
      and t.yearperiodcode=p_yearcode 
      and t.voucherdate<p_date
      and d.accountcode=p_accountcode 
      and d.subledgercode=p_subledgercode 
      and t.approvalstatus=9);
      return m_balance;
end;
end subledgeropeningbalance;
/

prompt
prompt Creating function SUBSCHEDULECLOSINGBALANCE
prompt ===========================================
prompt
create or replace function nst_nasaka_finance.subscheduleclosingbalance(p_yearcode in number,p_subschedulecode in number,p_date in date) return number is
begin
declare
m_balance number;
begin
      select nvl(sum(debitbalance),0)-nvl(sum(creditbalance),0) into m_balance
      from (
      select nvl(a.creditbalance,0) as creditbalance,nvl(a.debitbalance,0) as debitbalance 
      from accountopening a,accounthead aa,accountschedule s
      where a.accountcode= aa.accountcode
       and aa.schedulecode=s.schedulecode
       and a.yearperiodcode=p_yearcode 
       and aa.subschedulecode=p_subschedulecode
       and a.yearperiodcode=p_yearcode
      union all
      select nvl(sum(d.credit),0) as creditbalance,nvl(sum(d.debit),0) as debitbalance 
      from voucherheader t,voucherdetail d,accounthead aa,accountschedule s
      where t.transactionnumber=d.transactionnumber 
      and d.accountcode= aa.accountcode
       and aa.schedulecode=s.schedulecode
       and t.yearperiodcode=p_yearcode 
       and aa.subschedulecode=p_subschedulecode
       and t.yearperiodcode=p_yearcode
      and t.yearperiodcode=p_yearcode and t.voucherdate<=p_date
      and t.approvalstatus=9);
      return nvl(m_balance,0);
end;
end subscheduleclosingbalance;
/

prompt
prompt Creating function SUBSUBSCHEDULECLOSINGBALANCE
prompt ==============================================
prompt
create or replace function nst_nasaka_finance.subsubscheduleclosingbalance(p_yearcode in number,p_subsubschedulecode in number,p_date in date) return number is
begin
declare
m_balance number;
begin
      select nvl(sum(debitbalance),0)-nvl(sum(creditbalance),0) into m_balance
      from (
      select nvl(a.creditbalance,0) as creditbalance,nvl(a.debitbalance,0) as debitbalance 
      from accountopening a,accounthead aa,accountschedule s
      where a.accountcode= aa.accountcode
       and aa.schedulecode=s.schedulecode
       and a.yearperiodcode=p_yearcode 
       and aa.subsubschedulecode=p_subsubschedulecode
       and a.yearperiodcode=p_yearcode
      union all
      select nvl(sum(d.credit),0) as creditbalance,nvl(sum(d.debit),0) as debitbalance 
      from voucherheader t,voucherdetail d,accounthead aa,accountschedule s
      where t.transactionnumber=d.transactionnumber 
      and d.accountcode= aa.accountcode
       and aa.schedulecode=s.schedulecode
       and t.yearperiodcode=p_yearcode 
       and aa.subsubschedulecode=p_subsubschedulecode
       and t.yearperiodcode=p_yearcode
      and t.yearperiodcode=p_yearcode and t.voucherdate<=p_date
      and t.approvalstatus=9);
      return nvl(m_balance,0);
end;
end subsubscheduleclosingbalance;
/

prompt
prompt Creating function VOUCHERNUMBERBASEVALUE
prompt ========================================
prompt
create or replace function nst_nasaka_finance.vouchernumberbasevalue(pdate in date,presetcategoryid in number default 4) return varchar2 is
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
end vouchernumberbasevalue;
/

prompt
prompt Creating procedure BANKRECOCILATION
prompt ===================================
prompt
create or replace procedure nst_nasaka_finance.bankrecocilation(pbankaccountcode in number, fdt in date, tdt in date) is
begin
declare
mbankdate  date;
mfunddocumentcode  number;
mchequeddrtgsno number;
mcreditamount number;
mfunddocumentnumber number;
mvouchertransactionnumber number;
mchequeddserrialnumber number;
mpassbooktransactionnumber number;
mpassbookserialnumber number;
mfunddocumentamount number;
mdebitamount number;
mbrmaxno number;
mserialnumber number;


--cursor alltran is
/*select d.funddocumentcode, d.funddocumentnumber from voucherchequedddetail d,voucherheader h
where d.transactionnumber=h.transactionnumber
and d.bankaccountcode=pbankaccountcode
union all
select p.funddocumentcode,p.chequeddrtgsnoc funddocumentnumber from passbookdetail p ;*/

cursor passbook is
select p.headertransactionnumber,p.serialnumber, p.bankdate,p.chequeddrtgsno,
p.funddocumentcode,
p.creditamount,p.debitamount from passbookdetail p
order by p.serialnumber ;
cursor account is
select d.transactionnumber,d.funddocumentcode,d.funddocumentamount from voucherchequedddetail d,voucherheader h
where d.transactionnumber=h.transactionnumber
and d.bankaccountcode=pbankaccountcode;

cursor foundincchqdd is
select d.transactionnumber,d.detailserialnumber chequeddserrialnumber,
d.funddocumentamount from voucherchequedddetail d,voucherheader h
where d.transactionnumber=h.transactionnumber
and d.bankaccountcode=pbankaccountcode
and d.funddocumentcode =mfunddocumentcode
and d.funddocumentnumber=mfunddocumentnumber
and (d.funddocumentamount=mcreditamount or d.funddocumentamount= mdebitamount);
cursor foundindetail is
select d.transactionnumber,d.detailserialnumber chequeddserrialnumber
 from voucherdetail d,voucherheader h
where d.transactionnumber=h.transactionnumber
and d.accountcode=pbankaccountcode
and (d.debit=mcreditamount or d.credit= mdebitamount);

cursor brmaxno is
select nvl(max(r.transactionnumber),0) from reconcilationdetail r;

cursor accountbutnotinpass is
select d.transactionnumber,d.funddocumentcode,d.funddocumentnumber,d.funddocumentamount from voucherchequedddetail d,voucherheader h
where d.transactionnumber=h.transactionnumber
and d.bankaccountcode=pbankaccountcode
and h.voucherdate>=fdt and h.voucherdate<=tdt;
cursor notfoundinpasbook is
select d.serialnumber from passbookdetail d,passbookheader h
where d.headertransactionnumber=h.transactionnumber
and h.fromdate>=fdt and h.todate<=tdt
and h.bankaccountcode=pbankaccountcode
and d.chequeddrtgsno=mfunddocumentnumber
and (d.creditamount=mfunddocumentamount or d.debitamount =mfunddocumentamount)
and d.funddocumentcode=mfunddocumentcode;

begin
delete from reconcilationdetail t
where t.fromdate=fdt and t.todate=tdt;
commit;
open brmaxno;
fetch brmaxno into mbrmaxno;
close brmaxno;
open passbook;
loop
fetch passbook into mpassbooktransactionnumber,mpassbookserialnumber,
mbankdate,mfunddocumentnumber,mfunddocumentcode,mcreditamount,mdebitamount;
if passbook%notfound then 
  exit;
else
  mbrmaxno:=mbrmaxno+1;
  if nvl(mfunddocumentnumber,0)>0 then
        open foundincchqdd;
        fetch foundincchqdd into mvouchertransactionnumber,mchequeddserrialnumber,mfunddocumentamount;
        close foundincchqdd;
        if nvl(mvouchertransactionnumber,0) >0 then
      
        insert into reconcilationdetail
          (transactionnumber, passbooktransactionnumber, 
           vouchertransactionnumber, chequeddserrialnumber, 
           passbookserialnumber, reconciliationremarkcode, 
           chequeddnumber, amount,
           accountcode,fromdate,todate)
        values
          (mbrmaxno,mpassbooktransactionnumber,
           mvouchertransactionnumber, mchequeddserrialnumber,
           mpassbookserialnumber,1,
           mfunddocumentnumber, mfunddocumentamount,
           pbankaccountcode,fdt,tdt);
           end if;
      else       
           open foundindetail;
           fetch foundindetail into mvouchertransactionnumber,mchequeddserrialnumber;
           close foundindetail;
           
           if nvl(mvouchertransactionnumber,0) >0 then
               insert into reconcilationdetail
              (transactionnumber, passbooktransactionnumber, 
               vouchertransactionnumber, chequeddserrialnumber, 
               passbookserialnumber, reconciliationremarkcode, 
               chequeddnumber, amount,
                   accountcode,fromdate,todate)
               values
              (mbrmaxno,mpassbooktransactionnumber,
               mvouchertransactionnumber, mchequeddserrialnumber,
               mpassbookserialnumber,1,
               mfunddocumentnumber, mfunddocumentamount,
                pbankaccountcode,fdt,tdt);
            end if;
         end if;
         if mcreditamount>0 then
         mfunddocumentamount:=mcreditamount;
         else
          mfunddocumentamount:=mdebitamount;
         end if;
        if nvl(mvouchertransactionnumber,0)=0 then
            insert into reconcilationdetail
              (transactionnumber, passbooktransactionnumber, 
               vouchertransactionnumber, chequeddserrialnumber, 
               passbookserialnumber, reconciliationremarkcode, 
               chequeddnumber, amount,
                  accountcode,fromdate,todate)
            values
              (mbrmaxno,mpassbooktransactionnumber,
               0, 0,
               mpassbookserialnumber,3,
               0, mfunddocumentamount,
                pbankaccountcode,fdt,tdt);
        end if;
      commit;
    mpassbooktransactionnumber:=0;mpassbookserialnumber:=0; mfunddocumentamount:=0;
    mvouchertransactionnumber:=0; mchequeddserrialnumber:=0;
commit;
end if;
end loop;
close passbook;
commit;
----------- Reverse
open accountbutnotinpass;
loop
fetch accountbutnotinpass into   mvouchertransactionnumber,mfunddocumentcode,mfunddocumentnumber,mfunddocumentamount;
if accountbutnotinpass%notfound then
  exit;
  else
  open notfoundinpasbook;
  fetch notfoundinpasbook into mserialnumber;
  close notfoundinpasbook;
        if nvl(mserialnumber,0)=0 then
         mbrmaxno:=mbrmaxno+1;
           insert into reconcilationdetail
          (transactionnumber, passbooktransactionnumber, 
           vouchertransactionnumber, chequeddserrialnumber, 
           passbookserialnumber, reconciliationremarkcode, 
           chequeddnumber, amount,
              accountcode,fromdate,todate)
            values
          (mbrmaxno,0,
           mvouchertransactionnumber, mfunddocumentnumber,
           0,2,
           0, mfunddocumentamount,
            pbankaccountcode,fdt,tdt);
            commit;
        end if;
    mserialnumber:=0;
 end if;
 end loop;
 close accountbutnotinpass;
----

commit;
end;
end bankrecocilation;
/

prompt
prompt Creating procedure PROFITLOSS
prompt =============================
prompt
create or replace procedure nst_nasaka_finance.profitloss(p_yearcode in number,p_fromdate in date,p_todate in date) is
begin
declare
mprofitlosscur number;
mprofitcur number;
mopprofitcur number;
moplosscur number;
mclprofitcur number;
mcllosscur number;
mlosscur  number;

mprofitlosspre number;
mprofitpre number;
mopprofitpre number;
moplosspre number;
mclprofitpre number;
mcllosspre number;
mlosspre  number;


mdebit number;
mcredit number;
mtransactionnumber number;

cursor cpl_cur is
select  nvl(sum(nvl(d.debit,0)),0)debit ,nvl(sum(nvl(d.credit,0)),0) credit
from voucherheader h
,voucherdetail d 
,accounthead a
,accountgroup g
where h.transactionnumber=d.transactionnumber
and d.accountcode=a.accountcode
and a.groupcode=g.groupcode
and h.approvalstatus=9
and g.grouptypecode in (3,4)
and h.voucherdate>=p_fromdate 
and h.voucherdate<=p_todate
and h.yearperiodcode = p_yearcode;

cursor op_cur is
select case when nvl(a.debitbalance,0)>0 then nvl(a.debitbalance,0) else 0 end oploss
,case when nvl(a.creditbalance,0)>0 then nvl(a.creditbalance,0) else 0 end opprofit
 from accountopening a ,accountcontroltable t
 where a.accountcode=t.accruedprofitlossaccountcode
 and a.yearperiodcode=p_yearcode;

cursor cpl_pre is
select  nvl(sum(nvl(d.debit,0)),0)debit ,nvl(sum(nvl(d.credit,0)),0) credit
from voucherheader h
,voucherdetail d 
,accounthead a
,accountgroup g
where h.transactionnumber=d.transactionnumber
and d.accountcode=a.accountcode
and a.groupcode=g.groupcode
and h.approvalstatus=9
and g.grouptypecode in (3,4)
/*and h.voucherdate>=p_fromdate 
and h.voucherdate<=p_todate*/
and h.yearperiodcode = p_yearcode-10001;

cursor op_pre is
select case when nvl(a.debitbalance,0)>0 then nvl(a.debitbalance,0) else 0 end oploss
,case when nvl(a.creditbalance,0)>0 then nvl(a.creditbalance,0) else 0 end opprofit
 from accountopening a ,accountcontroltable t
 where a.accountcode=t.accruedprofitlossaccountcode
 and a.yearperiodcode=p_yearcode-10001;


begin
delete from profitandloss t
where trunc(t.todate)=p_todate and trunc(t.fromdate)=p_fromdate;
select nvl(max(t.transactionnumber),0)+1 into mtransactionnumber
 from  profitandloss t;
 --Current
 open cpl_cur;
 fetch cpl_cur into mdebit,mcredit;
 close cpl_cur;
 if mcredit>=mdebit then
 mprofitcur:= mcredit-mdebit;
 mlosscur:=0;
 else
 mlosscur := abs(mcredit-mdebit);
 mprofitcur:=0;
 end if;
 open op_cur;
 fetch op_cur into moplosscur,mopprofitcur;
 close op_cur;
 if (mopprofitcur+mprofitcur-(moplosscur+mlosscur))>0 then
    mclprofitcur:=mopprofitcur+mprofitcur-(moplosscur+mlosscur);
    mcllosscur:=0;
 else
    mcllosscur:=abs(mopprofitcur+mprofitcur-(moplosscur+mlosscur));
    mclprofitcur:=0;
 end if;
 
 --Previous
 open cpl_pre;
 fetch cpl_pre into mdebit,mcredit;
 close cpl_pre;
 if mcredit>=mdebit then
 mprofitpre:= mcredit-mdebit;
 mlosspre:=0;
 else
 mlosspre := abs(mcredit-mdebit);
 mprofitpre:=0;
 end if;
 open op_pre;
 fetch op_pre into moplosspre,mopprofitpre;
 close op_pre;
 if (mopprofitpre+mprofitpre-(moplosspre+mlosspre))>0 then
    mclprofitpre:=mopprofitpre+mprofitpre-(moplosspre+mlosspre);
    mcllosspre:=0;
 else
    mcllosspre:=abs(mopprofitpre+mprofitpre-(moplosspre+mlosspre));
    mclprofitpre:=0;
 end if;
 
 insert into profitandloss
   (transactionnumber, fromdate, todate,opprofitcur,oplosscur, profitcur, losscur,clprofitcur,cllosscur,opprofitpre,oplosspre, profitpre, losspre,clprofitpre,cllosspre)
 values
   (mtransactionnumber, p_fromdate, p_todate,mopprofitcur,moplosscur, mprofitcur, mlosscur,mclprofitcur,mcllosscur,mopprofitpre,moplosspre, mprofitpre, mlosspre,mclprofitpre,mcllosspre);
   commit;
end;
end profitloss;
/

prompt
prompt Creating procedure SALEINTERFACEFINANCE
prompt =======================================
prompt
create or replace procedure nst_nasaka_finance.saleinterfacefinance
(p_saledate in date,
 p_in number,
 p_salesubcategory in number,
 p_purchasercode in varchar2,
 p_basicvalue in number,
 p_cgst in number,
 p_sgst in number,
 p_ugst in number,
 p_igst in number,
 p_vat in number,
 p_excise in number,
 p_cess in number,
 P_gross in number,
 p_narration in varchar2,
 p_action in number,
 p_billtransactionnumber in number,
 p_yearcode in varchar2,
 p_transactionnumber out number
) is
begin
declare

begin
   commit;
end;
end saleinterfacefinance;
/


spool off
