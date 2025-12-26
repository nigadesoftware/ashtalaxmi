-------------------------------------------------------
-- Export file for user NST_SIDDHESHWAR_NSWS         --
-- Created by Siddheshwar on 06/08/2025, 10:43:00 AM --
-------------------------------------------------------

spool nsws_obj_10011.log

prompt
prompt Creating table FORMDEFINITION
prompt =============================
prompt
create table NST_SIDDHESHWAR_NSWS.FORMDEFINITION
(
  FORMID     NUMBER not null,
  SETTINGSID VARCHAR2(20),
  FORMNAME   VARCHAR2(100)
)
tablespace SYSTEM
  pctfree 10
  pctused 40
  initrans 1
  maxtrans 255
  storage
  (
    initial 64K
    next 1M
    minextents 1
    maxextents unlimited
  );
alter table NST_SIDDHESHWAR_NSWS.FORMDEFINITION
  add primary key (FORMID)
  using index 
  tablespace SYSTEM
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

prompt
prompt Creating table SECTIONDEFINITION
prompt ================================
prompt
create table NST_SIDDHESHWAR_NSWS.SECTIONDEFINITION
(
  SECTIONID               NUMBER not null,
  FORMID                  NUMBER,
  SECTIONNAME             VARCHAR2(100),
  ISMULTIPLEFIELDRESPONSE NUMBER
)
tablespace SYSTEM
  pctfree 10
  pctused 40
  initrans 1
  maxtrans 255
  storage
  (
    initial 64K
    next 1M
    minextents 1
    maxextents unlimited
  );
alter table NST_SIDDHESHWAR_NSWS.SECTIONDEFINITION
  add primary key (SECTIONID)
  using index 
  tablespace SYSTEM
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
alter table NST_SIDDHESHWAR_NSWS.SECTIONDEFINITION
  add foreign key (FORMID)
  references NST_SIDDHESHWAR_NSWS.FORMDEFINITION (FORMID);

prompt
prompt Creating table FIELDDEFINITION
prompt ==============================
prompt
create table NST_SIDDHESHWAR_NSWS.FIELDDEFINITION
(
  FIELDID                    NUMBER not null,
  FIELDNAME                  VARCHAR2(500),
  SECTIONID                  NUMBER,
  ISMULTIPLESUBFIELDRESPONSE NUMBER,
  ISSUBFIELDS                NUMBER,
  SEQUENCENUMBER             NUMBER,
  ISACTIVE                   NUMBER
)
tablespace SYSTEM
  pctfree 10
  pctused 40
  initrans 1
  maxtrans 255
  storage
  (
    initial 64K
    next 1M
    minextents 1
    maxextents unlimited
  );
alter table NST_SIDDHESHWAR_NSWS.FIELDDEFINITION
  add primary key (FIELDID)
  using index 
  tablespace SYSTEM
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
alter table NST_SIDDHESHWAR_NSWS.FIELDDEFINITION
  add foreign key (SECTIONID)
  references NST_SIDDHESHWAR_NSWS.SECTIONDEFINITION (SECTIONID);

prompt
prompt Creating table DEFAULTFIELDDATA
prompt ===============================
prompt
create table NST_SIDDHESHWAR_NSWS.DEFAULTFIELDDATA
(
  FIELDID         NUMBER not null,
  INPUTVALUE      VARCHAR2(1000),
  FACTORYUNITCODE NUMBER
)
tablespace SYSTEM
  pctfree 10
  pctused 40
  initrans 1
  maxtrans 255
  storage
  (
    initial 64K
    next 1M
    minextents 1
    maxextents unlimited
  );
alter table NST_SIDDHESHWAR_NSWS.DEFAULTFIELDDATA
  add constraint FKFLDID foreign key (FIELDID)
  references NST_SIDDHESHWAR_NSWS.FIELDDEFINITION (FIELDID);

prompt
prompt Creating table FACTORYUNIT
prompt ==========================
prompt
create table NST_SIDDHESHWAR_NSWS.FACTORYUNIT
(
  FACTORYUNITCODE NUMBER,
  FACTORYUNITNAME VARCHAR2(500)
)
tablespace SYSTEM
  pctfree 10
  pctused 40
  initrans 1
  maxtrans 255
  storage
  (
    initial 64K
    next 1M
    minextents 1
    maxextents unlimited
  );

prompt
prompt Creating table REPORTPERIOD
prompt ===========================
prompt
create table NST_SIDDHESHWAR_NSWS.REPORTPERIOD
(
  REPORTPERIODID          NUMBER not null,
  SEASON                  VARCHAR2(9),
  MONTH                   NUMBER,
  FACTORYUNITCODE         NUMBER,
  LASTMONTHREPORTPERIODID NUMBER
)
tablespace SYSTEM
  pctfree 10
  pctused 40
  initrans 1
  maxtrans 255
  storage
  (
    initial 64K
    next 1M
    minextents 1
    maxextents unlimited
  );
alter table NST_SIDDHESHWAR_NSWS.REPORTPERIOD
  add primary key (REPORTPERIODID)
  using index 
  tablespace SYSTEM
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
alter table NST_SIDDHESHWAR_NSWS.REPORTPERIOD
  add constraint UNSQ_SEAMON unique (SEASON, MONTH, FACTORYUNITCODE)
  using index 
  tablespace SYSTEM
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

prompt
prompt Creating table FIELDRESPONSE
prompt ============================
prompt
create table NST_SIDDHESHWAR_NSWS.FIELDRESPONSE
(
  RESPONSEID     NUMBER not null,
  FIELDID        NUMBER,
  REPORTPERIODID NUMBER,
  INPUTVALUE     VARCHAR2(1000),
  ISDEFAULTDATA  NUMBER
)
tablespace SYSTEM
  pctfree 10
  pctused 40
  initrans 1
  maxtrans 255
  storage
  (
    initial 64K
    next 1M
    minextents 1
    maxextents unlimited
  );
alter table NST_SIDDHESHWAR_NSWS.FIELDRESPONSE
  add primary key (RESPONSEID)
  using index 
  tablespace SYSTEM
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
alter table NST_SIDDHESHWAR_NSWS.FIELDRESPONSE
  add constraint FKREPID foreign key (REPORTPERIODID)
  references NST_SIDDHESHWAR_NSWS.REPORTPERIOD (REPORTPERIODID) on delete cascade;
alter table NST_SIDDHESHWAR_NSWS.FIELDRESPONSE
  add foreign key (FIELDID)
  references NST_SIDDHESHWAR_NSWS.FIELDDEFINITION (FIELDID);

prompt
prompt Creating table SETTINGS
prompt =======================
prompt
create table NST_SIDDHESHWAR_NSWS.SETTINGS
(
  SETTINGSID      NUMBER not null,
  APPROVALID      VARCHAR2(20),
  SWSID           VARCHAR2(20),
  PROJECTNUMBER   VARCHAR2(10),
  FACTORYUNITCODE NUMBER
)
tablespace SYSTEM
  pctfree 10
  pctused 40
  initrans 1
  maxtrans 255
  storage
  (
    initial 64K
    next 1M
    minextents 1
    maxextents unlimited
  );

prompt
prompt Creating table SUBFIELDDEFINITION
prompt =================================
prompt
create table NST_SIDDHESHWAR_NSWS.SUBFIELDDEFINITION
(
  SUBFIELDID     NUMBER not null,
  FIELDID        NUMBER,
  SUBFIELDNAME   VARCHAR2(100),
  SEQUENCENUMBER NUMBER
)
tablespace SYSTEM
  pctfree 10
  pctused 40
  initrans 1
  maxtrans 255
  storage
  (
    initial 64K
    next 1M
    minextents 1
    maxextents unlimited
  );
alter table NST_SIDDHESHWAR_NSWS.SUBFIELDDEFINITION
  add primary key (SUBFIELDID)
  using index 
  tablespace SYSTEM
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
alter table NST_SIDDHESHWAR_NSWS.SUBFIELDDEFINITION
  add foreign key (FIELDID)
  references NST_SIDDHESHWAR_NSWS.FIELDDEFINITION (FIELDID);

prompt
prompt Creating table SUBFIELDRESPONSE
prompt ===============================
prompt
create table NST_SIDDHESHWAR_NSWS.SUBFIELDRESPONSE
(
  SUBFIELDRESPONSEID NUMBER not null,
  FIELDRESPONSEID    NUMBER,
  SERIALNUMBER       NUMBER,
  SUBFIELDID         NUMBER,
  INPUTVALUE         VARCHAR2(4000)
)
tablespace SYSTEM
  pctfree 10
  pctused 40
  initrans 1
  maxtrans 255
  storage
  (
    initial 64K
    next 1M
    minextents 1
    maxextents unlimited
  );
alter table NST_SIDDHESHWAR_NSWS.SUBFIELDRESPONSE
  add primary key (SUBFIELDRESPONSEID)
  using index 
  tablespace SYSTEM
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
alter table NST_SIDDHESHWAR_NSWS.SUBFIELDRESPONSE
  add constraint UNQSUBSER unique (SUBFIELDRESPONSEID, SERIALNUMBER)
  using index 
  tablespace SYSTEM
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
alter table NST_SIDDHESHWAR_NSWS.SUBFIELDRESPONSE
  add foreign key (SUBFIELDID)
  references NST_SIDDHESHWAR_NSWS.SUBFIELDDEFINITION (SUBFIELDID);
alter table NST_SIDDHESHWAR_NSWS.SUBFIELDRESPONSE
  add foreign key (FIELDRESPONSEID)
  references NST_SIDDHESHWAR_NSWS.FIELDRESPONSE (RESPONSEID) on delete cascade;

prompt
prompt Creating function GETDATAJSON
prompt =============================
prompt
CREATE OR REPLACE FUNCTION NST_SIDDHESHWAR_NSWS.getdatajson(p_reportperiodid IN NUMBER)
RETURN CLOB
IS
    -- Variables for constructing the JSON
    l_json_clob   CLOB := '';
    mfieldname    VARCHAR2(500);
    m_inputvalue  VARCHAR2(500);
    msectionid    NUMBER;
    msectionname  VARCHAR2(500);
    msrno         NUMBER;
    msrnosection  NUMBER;
    mformid       NUMBER;
    mformname     VARCHAR2(500);
    mfieldid      NUMBER;
    msubfieldname VARCHAR2(500);
    msrnosubfield NUMBER;
    missubfields  NUMBER;
    msettingsid   NUMBER;
    mapprovalid   VARCHAR2(500);
    mswsid        VARCHAR2(500);
    mprojectnumber VARCHAR2(500);
    msrnosettings NUMBER;

    -- Cursors
    CURSOR c_settings IS
        SELECT f.settingsid, f.approvalid, f.swsid, f.projectnumber
        FROM settings f;

    CURSOR c_form IS
        SELECT f.formid, f.formname
        FROM formdefinition f
        WHERE f.settingsid = msettingsid;

    CURSOR c_section IS
        SELECT s.sectionid, s.sectionname
        FROM sectiondefinition s
        WHERE s.formid = mformid;

    CURSOR c_field IS
        SELECT f.fieldid, f.fieldname, t.inputvalue AS field_inputvalue, NVL(f.issubfields, 0) issubfields
        FROM fieldresponse t, fielddefinition f, sectiondefinition s
        WHERE t.fieldid = f.fieldid
          AND f.sectionid = s.sectionid
          AND s.formid = mformid
          AND t.reportperiodid = p_reportperiodid
          AND f.sectionid = msectionid;

    CURSOR c_subfield IS
        SELECT f.subfieldname, t.inputvalue AS subfield_inputvalue
        FROM subfieldresponse t, subfielddefinition f, fieldresponse r
        WHERE t.subfieldid = f.subfieldid
          AND f.fieldid = mfieldid
          AND t.fieldresponseid = r.responseid
          AND r.reportperiodid = p_reportperiodid;

BEGIN
    -- Initialize the JSON
    l_json_clob := l_json_clob || '[' || CHR(10);

    -- Loop through settings
    OPEN c_settings;
    msrnosettings := 0;
    LOOP
        FETCH c_settings INTO msettingsid, mapprovalid, mswsid, mprojectnumber;
        EXIT WHEN c_settings%NOTFOUND;

        IF msrnosettings > 0 THEN
            l_json_clob := l_json_clob || ',' || CHR(10);
        END IF;

        l_json_clob := l_json_clob || '{' || CHR(10);

        l_json_clob := l_json_clob || '"approvalId": "'||mapprovalid||'",' || CHR(10);
        l_json_clob := l_json_clob || '"swsId": "'||mswsid||'",' || CHR(10);
        l_json_clob := l_json_clob || '"projectNumber": "'||mprojectnumber||'",' || CHR(10);
        l_json_clob := l_json_clob || '"forms": [' || CHR(10);

        -- Loop through forms
        OPEN c_form;
        msrnosection := 0;
        LOOP
            FETCH c_form INTO mformid, mformname;
            EXIT WHEN c_form%NOTFOUND;

            IF msrnosection > 0 THEN
                l_json_clob := l_json_clob || ',' || CHR(10);
            END IF;

            l_json_clob := l_json_clob || CHR(9) || '{' || CHR(10);
            l_json_clob := l_json_clob || CHR(9) || '"Name": "' || mformname || '",' || CHR(10);
            l_json_clob := l_json_clob || CHR(9) || '"sections": [' || CHR(10);

            -- Loop through sections
            OPEN c_section;
            msrnosection := 0;
            LOOP
                FETCH c_section INTO msectionid, msectionname;
                EXIT WHEN c_section%NOTFOUND;

                IF msrnosection > 0 THEN
                    l_json_clob := l_json_clob || ',' || CHR(10);
                END IF;

                l_json_clob := l_json_clob || CHR(9) || '{' || CHR(10);
                l_json_clob := l_json_clob || '"sectionName": "' || msectionname || '",' || CHR(10);
                l_json_clob := l_json_clob || '"fieldResponses": [' || CHR(10);

                -- Loop through fields
                OPEN c_field;
                msrno := 0;
                LOOP
                    FETCH c_field INTO mfieldid, mfieldname, m_inputvalue, missubfields;
                    EXIT WHEN c_field%NOTFOUND;

                    IF msrno > 0 THEN
                        l_json_clob := l_json_clob || ',' || CHR(10);
                    END IF;


                    -- Remove extra slashes if they exist
                    --m_inputvalue := REPLACE(m_inputvalue, '\"', '"');

                    -- Then proceed with the same logic

                    IF missubfields = 0 THEN
                        l_json_clob := l_json_clob || '{' || CHR(10);
                        l_json_clob := l_json_clob || '"fieldName": "' || mfieldname || '",' || CHR(10);
                        IF m_inputvalue LIKE '[%' AND m_inputvalue LIKE '%]' THEN
                           l_json_clob := l_json_clob || '"inputValue": "' || m_inputvalue || '"' || CHR(10);
                        ELSE
                           l_json_clob := l_json_clob || '"inputValue": "' || m_inputvalue || '"' || CHR(10);
                        END IF;
                        l_json_clob := l_json_clob || '}';
                    ELSE
                        l_json_clob := l_json_clob || '{' || CHR(10);
                        l_json_clob := l_json_clob || '"fieldName": "' || mfieldname || '",' || CHR(10);
                        l_json_clob := l_json_clob || '"subfields": [' || CHR(10);

                        -- Loop through subfields
                        OPEN c_subfield;
                        msrnosubfield := 0;
                        LOOP
                            FETCH c_subfield INTO msubfieldname, m_inputvalue;
                            EXIT WHEN c_subfield%NOTFOUND;

                            IF msrnosubfield > 0 THEN
                                l_json_clob := l_json_clob || ',' || CHR(10);
                            END IF;

                            l_json_clob := l_json_clob || '{' || CHR(10);
                            l_json_clob := l_json_clob || '"subfieldName": "' || msubfieldname || '",' || CHR(10);
                            l_json_clob := l_json_clob || '"inputvalue": "' || m_inputvalue || '"' || CHR(10);
                            l_json_clob := l_json_clob || '}';
                            msrnosubfield := msrnosubfield + 1;
                        END LOOP;

                        CLOSE c_subfield;
                        l_json_clob := l_json_clob || ']' || CHR(10);
                        l_json_clob := l_json_clob || '}';
                    END IF;

                    msrno := msrno + 1;
                END LOOP;

                CLOSE c_field;
                l_json_clob := l_json_clob || ']' || CHR(10);
                l_json_clob := l_json_clob || '}';
                msrnosection := msrnosection + 1;
            END LOOP;

            CLOSE c_section;
            l_json_clob := l_json_clob || ']' || CHR(10);
            l_json_clob := l_json_clob || '}';
            msrnosection := msrnosection + 1;
        END LOOP;

        CLOSE c_form;
        l_json_clob := l_json_clob || ']' || CHR(10);
        l_json_clob := l_json_clob || '}';
        msrnosettings := msrnosettings + 1;
    END LOOP;

    CLOSE c_settings;
    l_json_clob := l_json_clob || ']';

    -- Return the final JSON
    RETURN l_json_clob;
END getdatajson;
/

prompt
prompt Creating procedure GETFIELDINPUTDATA
prompt ====================================
prompt
create or replace procedure nst_siddheshwar_nsws.getfieldinputdata(p_reportperiodid in number,p_formid in number,p_season in varchar2,p_month varchar2,p_factoryunitcode in number,p_lastmonthreportperiodid in number default null) is
begin
  declare

  m_sectionid number;
  m_sectionname varchar2(500);
  m_fieldid number;
  m_fieldname varchar2(500);
  m_subfieldid number;
  m_subfieldname varchar2(500);
  m_inputvalue varchar(500);
  m_responseid number;
  m_serialnumber number;
  m_isdefaultdata number;
  m_issubfields number;
  m_lastmonthreportperiodid number;
  m_lastmonthinputvalue varchar2(500);

  cursor c_section is
  select s.sectionid,s.sectionname
  from sectiondefinition s
  where s.formid=p_formid
  order by s.sectionid;

  cursor c_field is
  select f.fieldid,f.fieldname,f.issubfields
  from fielddefinition f
  where f.sectionid=m_sectionid
  and f.isactive=1
  order by f.sequencenumber;


  cursor c_subfield is
  select f.subfieldid,f.subfieldname
  from subfielddefinition f
  where f.fieldid=m_fieldid
  order by f.sequencenumber;

  cursor c_defaultdata is
  select d.inputvalue
  from defaultfielddata d
  where d.fieldid=m_fieldid and (d.factoryunitcode=p_factoryunitcode or d.factoryunitcode is null);

/*  cursor c1 is
  select r.season
  from reportperiod r
  where r.reportperiodid=p_reportperiodid;

  cursor c2 is
  select trim(TO_CHAR(TO_DATE(r.month, 'MM'), 'Month'))
  from reportperiod r
  where r.reportperiodid=p_reportperiodid;*/

  /*cursor c_lastmonthperiodid is
  select p.reportperiodid
  from reportperiod p
  where p.season=p_season and p.factoryunitcode=p_factoryunitcode
  and p.month =
        (case when p_month = '1' then '12' else to_char(p_month - 1) end);*/

  cursor c_lastmonthsubfieldidvalue is
  select s.inputvalue
  from subfieldresponse s,fieldresponse f
  where s.fieldresponseid=f.responseid
  and f.reportperiodid=p_lastmonthreportperiodid
  and f.fieldid in (45,46,47,48,49,50)
  and s.subfieldid=m_subfieldid;

begin
  delete from fieldresponse t where t.reportperiodid=p_reportperiodid;
  /*open c_lastmonthperiodid;
  fetch c_lastmonthperiodid into m_lastmonthreportperiodid;
  close c_lastmonthperiodid;*/
  open c_section;
  loop
       fetch c_section into m_sectionid,m_sectionname;
       if c_section%notfound then
          exit;
       end if;
       open c_field;
       loop
            fetch c_field into m_fieldid,m_fieldname,m_issubfields;
            if c_field%notfound then
               exit;
            end if;
            open c_defaultdata;
            fetch c_defaultdata into m_inputvalue;
            close c_defaultdata;
            m_isdefaultdata:=1;
            if m_inputvalue is null then
                case m_fieldid
                  when 1 then
                    /*open c1;
                    fetch c1 into m_inputvalue;
                    close c1;*/
                    m_inputvalue:=p_season;
                  when 2 then
                    /*open c2;
                    fetch c2 into m_inputvalue;
                    close c2;*/
                    m_inputvalue:=p_month;
                  else
                    m_inputvalue:='0';
                    m_isdefaultdata:=0;
                end case;
            end if;
            if m_issubfields=1 then
              m_inputvalue:=null;
            end if;
            select nvl(max(responseid),0)+1 into m_responseid from fieldresponse f;

            insert into fieldresponse
              (responseid, fieldid, reportperiodid, inputvalue,isdefaultdata)
            values
              (m_responseid, m_fieldid, p_reportperiodid, m_inputvalue,m_isdefaultdata);

            m_inputvalue:=null;
            m_serialnumber:=1;
            open c_subfield;
            loop
                fetch c_subfield into m_subfieldid,m_subfieldname;
                if c_subfield%notfound then
                   exit;
                end if;

                case m_subfieldid
                  when 0 then
                    /*open c1;
                    fetch c1 into m_inputvalue;
                    close c1;*/
                    m_inputvalue:=p_season;
                   when 25 then
                    m_inputvalue:='01/04/2000';
                   when 29 then
                    m_inputvalue:='01/04/2000';
                    when 33 then
                    m_inputvalue:='01/04/2000';
                    when 37 then
                    m_inputvalue:='01/04/2000';
                    when 41 then
                    m_inputvalue:='01/04/2000';
                    when 53 then
                    m_inputvalue:='01/04/2000';
                    when 57 then
                    m_inputvalue:='01/04/2000';
                    when 61 then
                    m_inputvalue:='01/04/2000';
                    when 65 then
                    m_inputvalue:='01/04/2000';
                  else
                    m_inputvalue:='0';
                end case;
                open c_lastmonthsubfieldidvalue;
                fetch c_lastmonthsubfieldidvalue into m_lastmonthinputvalue;
                close c_lastmonthsubfieldidvalue;
                if m_lastmonthinputvalue is not null then
                   m_inputvalue:=m_lastmonthinputvalue;
                end if;
                insert into subfieldresponse
                  (subfieldresponseid, fieldresponseid, serialnumber, subfieldid, inputvalue)
                values
                  ((select nvl(max(subfieldresponseid),0)+1 from subfieldresponse f), m_responseid, m_serialnumber, m_subfieldid, m_inputvalue);

                m_inputvalue:=null;
            end loop;
            close c_subfield;

      end loop;
      close c_field;
  end loop;
  close c_section;
  --commit;
  end;
end getfieldinputdata;
/

prompt
prompt Creating trigger TRG_AF_INS
prompt ===========================
prompt
create or replace trigger NST_SIDDHESHWAR_NSWS.trg_af_ins
  after insert
  on REPORTPERIOD
  for each row
declare
  -- local variables here
begin
  getfieldinputdata(p_reportperiodid => :new.reportperiodid,p_formid => 1,p_season => :New.Season,p_month => trim(TO_CHAR(TO_DATE(:new.month, 'MM'), 'Month')),p_factoryunitcode => :new.factoryunitcode,p_lastmonthreportperiodid =>:new.lastmonthreportperiodid );
end trg_af_ins;
/

prompt
prompt Creating trigger TRG_INS_REPORTPERIOD
prompt =====================================
prompt
create or replace trigger NST_SIDDHESHWAR_NSWS.trg_ins_reportperiod
  before insert
  on REPORTPERIOD
  for each row
declare
  -- local variables here
  m_reportperiodid number;
  
  cursor c1 is
  select p.reportperiodid
  from reportperiod p
  where p.season=:new.season and p.factoryunitcode=:new.factoryunitcode
  and p.month = 
        (case when :new.month = 1 then 12 else :new.month-1 end);
begin
  select nvl(max(r.reportperiodid),0)+1 into :new.reportperiodid from reportperiod r ;
  open c1;
  fetch c1 into m_reportperiodid;
  close c1;
  :new.lastmonthreportperiodid:=m_reportperiodid;
end trg_ins_reportperiod;
/


spool off
