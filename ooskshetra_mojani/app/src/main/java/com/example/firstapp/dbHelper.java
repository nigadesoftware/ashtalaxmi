package com.example.firstapp;
import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Date;
import java.util.HashMap;
import java.util.Hashtable;
import java.util.Locale;
import java.util.Random;

import android.content.ContentValues;
import android.content.Context;
import android.database.Cursor;
import android.database.DatabaseUtils;
import android.database.sqlite.SQLiteOpenHelper;
import android.database.sqlite.SQLiteDatabase;
import android.location.Location;
import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import android.util.Base64;
import android.util.Log;

import static com.android.volley.VolleyLog.TAG;

public class dbHelper extends SQLiteOpenHelper {

        public static final String DATABASE_NAME = "Agri.db";
        public static final String PLOTLIST_TABLE_NAME = "plotlist";
        public static final String PLOTLIST_COLUMN_ID = "id";
        public static final String PLOTLIST_COLUMN_SEASONCODE = "seasoncode";
        public static final String PLOTLIST_COLUMN_PLOTNUMBER = "plotnumber";
        public static final String PLOTLIST_COLUMN_FARMERNAME = "farmername";
        public static final String PLOTLIST_COLUMN_VILLAGENAME = "villagename";
        public static final String PLOTLIST_COLUMN_GATSURVENO = "gatsurveno";
        public static final String PLOTLIST_COLUMN_VARIETY = "variety";
        public static final String PLOTLIST_COLUMN_AREA = "area";
        public static final String PLOTLIST_COLUMN_REMARK = "remark";
        public static final String PLOTLIST_COLUMN_INAREAOUTAREA = "inareaoutarea";

        public static final String PLOTAREADETAIL_TABLE_NAME = "plotareadetail";
        public static final String PLOTAREADETAIL_COLUMN_ID = "id";
        public static final String PLOTAREADETAIL_COLUMN_SEASONCODE = "seasoncode";
        public static final String PLOTAREADETAIL_COLUMN_PLOTNUMBER = "plotnumber";
        public static final String PLOTAREADETAIL_COLUMN_SERIALNUMBER = "serialnumber";
        public static final String PLOTAREADETAIL_COLUMN_LATITUDE = "latitude";
        public static final String PLOTAREADETAIL_COLUMN_LONGITUDE = "longitude";

        public static final String TODSLIPLIST_TABLE_NAME = "todsliplist";
        public static final String TODSLIPLIST_COLUMN_ID = "id";
        public static final String TODSLIPLIST_COLUMN_SEASONCODE = "seasoncode";
        public static final String TODSLIPLIST_COLUMN_TODSLIPNUMBER = "todslipnumber";
        public static final String TODSLIPLIST_COLUMN_TODSLIPDATE = "todslipdate";
        public static final String TODSLIPLIST_COLUMN_PLOTNUMBER = "plotnumber";
        public static final String TODSLIPLIST_COLUMN_FARMERCATEGORYCODE = "farmercategorycode";
        public static final String TODSLIPLIST_COLUMN_FARMERCODE = "farmercode";
        public static final String TODSLIPLIST_COLUMN_VILLAGECODE = "villagecode";
        public static final String TODSLIPLIST_COLUMN_VEHICLECATEGORYCODE = "vehiclecategorycode";
        public static final String TODSLIPLIST_COLUMN_VEHICLECODE = "vehiclecode";
        public static final String TODSLIPLIST_COLUMN_HRSUBCONTRACTORCODE = "hrsubcontractorcode";
        public static final String TODSLIPLIST_COLUMN_TRSUBCONTRACTORCODE = "trsubcontractorcode";
        public static final String TODSLIPLIST_COLUMN_HRTRSUBCONTRACTORCODE = "hrtrsubcontractorcode";
        public static final String TODSLIPLIST_COLUMN_CANECONDITIONCODE = "caneconditioncode";
        public static final String TODSLIPLIST_COLUMN_SLIPBOYCODE = "slipboycode";
        public static final String TODSLIPLIST_COLUMN_FARMERNAMEUNI = "farmernameuni";
        public static final String TODSLIPLIST_COLUMN_VILLAGENAMEUNI = "villagenameuni";
        public static final String TODSLIPLIST_COLUMN_VEHICLENUMBER = "vehiclenumber";
        public static final String TODSLIPLIST_COLUMN_TRANSPORTERNAMEUNI = "transporternameuni";
        public static final String TODSLIPLIST_COLUMN_HARVESTERNAMEUNI = "harvesternameuni";
        public static final String TODSLIPLIST_COLUMN_HARVESTERTRANSPORTERNAMEUNI = "harvestertransporternameuni";
        public static final String TODSLIPLIST_COLUMN_LASTSRNO = "lastsrno";

        private HashMap hp;

        public dbHelper(Context context) {
            super(context, DATABASE_NAME , null, 1);
        }

        @Override
        public void onCreate(SQLiteDatabase db) {
            // TODO Auto-generated method stub
            db.execSQL(
                    "create table loginuser " +
                            "(userid integer);");
            db.execSQL(
                    "create table plotlist " +
                            "(seasoncode integer,plotnumber integer,farmername text, villagename text,gatsurveno text,variety text,area real,remark text,selfie blob,passbook blob,aadhar blob,inareaoutarea integer,pointcount integer,selfiecount integer,idcount integer,pbcount integer, PRIMARY KEY (seasoncode, plotnumber));");
            db.execSQL(
                    "create table plotareadetail " +
                            "( seasoncode integer,plotnumber integer,serialnumber integer,latitude real,longitude real, PRIMARY KEY (seasoncode, plotnumber,serialnumber),CONSTRAINT fkplot FOREIGN KEY (seasoncode,plotnumber) REFERENCES plotlist(seasoncode,plotnumber) ON DELETE CASCADE);");
            db.execSQL(
                    "create table todsliplist " +
                            "(seasoncode integer,todslipnumber integer, todslipdate date,plotnumber integer,farmercategorycode integer,farmercode integer,villagecode integer,hrsubcontractorcode integer,trsubcontractorcode integer,hrtrsubcontractorcode integer,caneconditioncode integer,slipboycode integer,farmernameuni text,villagenameuni text,transporternameuni text,harvesternameuni text,harvestertransporternameuni text,gatsurveno text,iscompleted integer,lastserialnumber integer, PRIMARY KEY (seasoncode, todslipnumber));");
            db.execSQL(
                    "create table todslipvehiclelist " +
                            "(seasoncode integer,todslipnumber integer,vehiclecategorycode integer,vehiclecode integer,tyregadicode integer, vehiclenumber text, tyregadinumber integer, gadiwanname text, UNIQUE (seasoncode, todslipnumber, vehiclecode,tyregadicode));");
            db.execSQL(
                    "create table fieldslip " +
                            "(seasoncode integer,fieldslipnumber integer,fieldslipdate date,plotnumber number,farmercategorycode number,farmercode number,villagecode number,subvillagecode number,vehiclecategorycode number,vehiclecode number,tyregadicode number,hrsubcontractorcode number,hrtrsubcontractorcode number,trsubcontractorcode number,caneconditioncode number,slipboycode number,distance number,layercode number,trailornumber text,containercode number,viadistance number,todslipnumber number, primary key (seasoncode,fieldslipnumber));");
            db.execSQL(
                    "create table container " +
                            "(containercode integer,containername text, primary key (containercode));");
            db.execSQL(
                    "create table settings " +
                            "(server integer,staticip1 text,staticip2 text,staticip3 text, primary key (server));");
            db.execSQL(
                    "insert into container " +
                            "(containercode,containername) values (1,'पुढील ट्रेलर/बाॅडी'); ");
            db.execSQL(
                    "insert into container " +
                            "(containercode,containername) values (2,'मागील ट्रेलर'); ");
            db.execSQL(
                    "insert into container " +
                            "(containercode,containername) values (3,'पुढील आणि मागील ट्रेलर'); ");
            db.execSQL(
                    "create table layer " +
                            "(layercode integer,layername text, primary key (layercode));");
            db.execSQL(
                    "insert into layer " +
                            "(layercode,layername) values (1,'वरचा (T)'); ");
            db.execSQL(
                    "insert into layer " +
                            "(layercode,layername) values (2,'मधला (M)'); ");
            db.execSQL(
                    "insert into layer " +
                            "(layercode,layername) values (3,'खालचा (B)'); ");
            db.execSQL(
                    "insert into layer " +
                            "(layercode,layername) values (9,'संपुर्ण (F)'); ");
            db.execSQL(
                    "insert into layer " +
                            "(layercode,layername) values (4,'पुढील संपुर्ण व मागील वरचा (1F2T)'); ");
            db.execSQL(
                    "insert into layer " +
                            "(layercode,layername) values (5,'पुढील संपुर्ण व मागील खालचा (1F2B)'); ");
            db.execSQL(
                    "insert into layer " +
                            "(layercode,layername) values (6,'पुढील वरचा व मागील संपुर्ण (1T2F)'); ");
            db.execSQL(
                    "insert into layer " +
                            "(layercode,layername) values (7,'पुढील खालचा व मागील संपुर्ण (1B2F)'); ");


            db.execSQL(
                    "insert into settings " +
                            "(server,staticip1,staticip2,staticip3) values (1,'http://206.84.230.83','http://206.84.230.83','http://206.84.230.83'); ");
        }

        @Override
        public void onUpgrade(SQLiteDatabase db, int oldVersion, int newVersion) {
            // TODO Auto-generated method stub
            db.execSQL("DROP TABLE IF EXISTS loginuser");
            db.execSQL("DROP TABLE IF EXISTS plotlist");
            db.execSQL("DROP TABLE IF EXISTS plotareadetail");
            db.execSQL("DROP TABLE IF EXISTS todsliplist");
            db.execSQL("DROP TABLE IF EXISTS todslipvehiclelist");
            db.execSQL("DROP TABLE IF EXISTS fieldslip");
            db.execSQL("DROP TABLE IF EXISTS container");
            db.execSQL("DROP TABLE IF EXISTS layer");
            db.execSQL("DROP TABLE IF EXISTS settings");
            onCreate(db);
        }

        public boolean insertLoginuser (long userid)
        {
            SQLiteDatabase db = this.getWritableDatabase();
            db.delete("loginuser",null,null);
            ContentValues contentValues = new ContentValues();
            contentValues.put("userid", userid);

            db.insert("loginuser", null, contentValues);
            db.close();
            return true;
        }

    public boolean updateServer (Integer serverid)
    {
        SQLiteDatabase db = this.getWritableDatabase();
        ContentValues contentValues = new ContentValues();
        contentValues.put("server", serverid);

        db.update("settings",contentValues, null, null);
        db.close();
        return true;
    }

        public boolean deleteLoginuser ()
        {
            SQLiteDatabase db = this.getWritableDatabase();
            db.delete("loginuser",null,null);
            db.close();
            return true;
        }

        public void setUid()
        {
            SQLiteDatabase db = this.getReadableDatabase();
            Cursor res =  db.rawQuery( "select userid  from loginuser;", null );
            if (res.getCount() >0) {
                res.moveToFirst();
                if (res.isAfterLast() == false)
                {
                    Global.uid = res.getLong(res.getColumnIndex("userid"));
                }
                else
                {
                    Global.uid = 0;
                }
            }
            else
            {
                Global.uid = Long.parseLong("0");
            }
            db.close();
        }
        public Long setInnerPassword()
        {
            Integer dtno = Integer.parseInt(new SimpleDateFormat("ddMMyyyy", Locale.getDefault()).format(new Date()));
            Long no;
            no=(Global.uid + dtno)*2;
            return no;
        }
        public void setStaticip()
        {
            SQLiteDatabase db = this.getReadableDatabase();
            Cursor res =  db.rawQuery( "select server,staticip1,staticip2,staticip3  from settings;", null );
            if (res.getCount() >0) {
                res.moveToFirst();
                if (res.isAfterLast() == false)
                {
                    if (res.getInt(res.getColumnIndex("server")) == 1)
                    {
                        Global.staticip = res.getString(res.getColumnIndex("staticip1"));
                    }
                    else if (res.getInt(res.getColumnIndex("server")) == 2)
                    {
                        Global.staticip = res.getString(res.getColumnIndex("staticip2"));
                    }
                    else if (res.getInt(res.getColumnIndex("server")) == 3)
                    {
                        Global.staticip = res.getString(res.getColumnIndex("staticip3"));
                    }
                    else
                    {
                        Global.staticip = res.getString(res.getColumnIndex("staticip1"));
                    }
                }
                else
                {
                    Global.staticip = "0";
                           }
            }
            else
            {
                Global.staticip = "0";
            }
            db.close();
        }

        public boolean insertPLot (Integer seasoncode, Integer plotnumber, String farmername, String villagename,String gatsurveno,String variety,Integer inareaoutarea)
        {
            SQLiteDatabase db = this.getWritableDatabase();
            ContentValues contentValues = new ContentValues();
            contentValues.put("seasoncode", seasoncode);
            contentValues.put("plotnumber", plotnumber);
            contentValues.put("farmername", farmername);
            contentValues.put("villagename", villagename);
            contentValues.put("gatsurveno", gatsurveno);
            contentValues.put("variety", variety);
            contentValues.put("inareaoutarea", inareaoutarea);
            db.insert("plotlist", null, contentValues);
            db.close();
            return true;
        }
        public boolean insertTodslip (Integer seasoncode, Integer todslipnumber, String todslipdate, Integer plotnumber, Integer farmercategorycode,Integer farmercode, Integer villagecode,Integer hrsubcontractorcode,Integer trsubcontractorcode,Integer hrtrsubcontractorcode,Integer caneconditioncode,Integer slipboycode,String farmernameuni,String villagenameuni,String transporternameuni,String harvesternameuni,String harvestertransporternameuni,String gatsurveno,Integer lastserialnumber)
        {
            SQLiteDatabase db = this.getWritableDatabase();
            ContentValues contentValues = new ContentValues();
            contentValues.put("seasoncode", seasoncode);
            contentValues.put("todslipnumber", todslipnumber);
            contentValues.put("todslipdate", todslipdate);
            contentValues.put("plotnumber", plotnumber);
            contentValues.put("farmercategorycode", farmercategorycode);
            contentValues.put("farmercode", farmercode);
            contentValues.put("villagecode", villagecode);
            contentValues.put("hrsubcontractorcode", hrsubcontractorcode);
            contentValues.put("trsubcontractorcode", trsubcontractorcode);
            contentValues.put("hrtrsubcontractorcode", hrtrsubcontractorcode);
            contentValues.put("caneconditioncode", caneconditioncode);
            contentValues.put("slipboycode", slipboycode);
            contentValues.put("farmernameuni", farmernameuni);
            contentValues.put("villagenameuni", villagenameuni);
            contentValues.put("transporternameuni", transporternameuni);
            contentValues.put("harvesternameuni", harvesternameuni);
            contentValues.put("harvestertransporternameuni", harvestertransporternameuni);
            contentValues.put("gatsurveno", gatsurveno);
            contentValues.put("lastserialnumber", lastserialnumber);
            db.insert("todsliplist", null, contentValues);
            db.close();
            return true;
        }
        public boolean insertTodslipvehiclelist (Integer seasoncode, Integer todslipnumber, Integer vehiclecategorycode,Integer vehiclecode, Integer tyregadicode,String vehiclenumber,Integer tyregadinumber,String gadiwanname)
        {
            SQLiteDatabase db = this.getWritableDatabase();
            ContentValues contentValues = new ContentValues();
            contentValues.put("seasoncode", seasoncode);
            contentValues.put("todslipnumber", todslipnumber);
            contentValues.put("vehiclecategorycode", vehiclecategorycode);
            contentValues.put("vehiclecode", vehiclecode);
            contentValues.put("tyregadicode", tyregadicode);
            contentValues.put("vehiclenumber", vehiclenumber);
            contentValues.put("tyregadinumber", tyregadinumber);
            contentValues.put("gadiwanname", gadiwanname);
            db.insert("todslipvehiclelist", null, contentValues);
            db.close();
            return true;

        }
        private String getDateTime()
        {
            SimpleDateFormat dateFormat = new SimpleDateFormat(
                    "yyyy-MM-dd HH:mm:ss", Locale.getDefault());
            Date date = new Date();
            return dateFormat.format(date);
        }
        public long insertFieldsliplist (Integer seasoncode, Integer todslipnumber,Integer fieldslipnumber,Integer tyregadicode,Integer containercode,Integer layercode)
        {
            SQLiteDatabase db = this.getWritableDatabase();
            long ret;
            int lastserialnumber;
            Cursor res =  db.rawQuery( "select t.*,l.vehiclecategorycode from todsliplist t,todslipvehiclelist l where t.seasoncode=l.seasoncode and t.todslipnumber=l.todslipnumber and t.seasoncode="+Integer.toString(seasoncode)+ " and t.todslipnumber="+Integer.toString(todslipnumber)+" and (l.vehiclecode="+Integer.toString(tyregadicode)+" or l.tyregadicode="+Integer.toString(tyregadicode)+")", null );
            if (res.getCount() >0)
            {
                res.moveToFirst();
                ContentValues contentValues = new ContentValues();
                contentValues.put("seasoncode", seasoncode);
                contentValues.put("fieldslipnumber", fieldslipnumber);
                contentValues.put("fieldslipdate", getDateTime());
                contentValues.put("plotnumber", res.getInt(res.getColumnIndex("plotnumber")));
                contentValues.put("farmercategorycode", res.getInt(res.getColumnIndex("farmercategorycode")));
                contentValues.put("villagecode", res.getInt(res.getColumnIndex("villagecode")));
                /*if(res.isNull(res.getInt(res.getColumnIndex("subvillagecode")))==false) {
                    contentValues.put("subvillagecode", res.getInt(res.getColumnIndex("subvillagecode")));
                }*/
                contentValues.put("vehiclecategorycode", res.getInt(res.getColumnIndex("vehiclecategorycode")));
                if (res.getInt(res.getColumnIndex("vehiclecategorycode"))==3)
                {
                    contentValues.put("tyregadicode", tyregadicode);
                }
                else
                {
                    contentValues.put("vehiclecode", tyregadicode);
                }
                contentValues.put("hrsubcontractorcode", res.getInt(res.getColumnIndex("hrsubcontractorcode")));
                contentValues.put("hrtrsubcontractorcode", res.getInt(res.getColumnIndex("hrtrsubcontractorcode")));
                contentValues.put("trsubcontractorcode", res.getInt(res.getColumnIndex("trsubcontractorcode")));
                contentValues.put("caneconditioncode", res.getInt(res.getColumnIndex("caneconditioncode")));
                contentValues.put("slipboycode", res.getInt(res.getColumnIndex("slipboycode")));
                //contentValues.put("distance", res.getInt(res.getColumnIndex("distance")));
                contentValues.put("layercode", layercode);
                contentValues.put("containercode", containercode);
                contentValues.put("todslipnumber", todslipnumber);
                //contentValues.put("viadistance", res.getInt(res.getColumnIndex("viadistance")));
                ret=0;
                try
                {
                    ret=db.insert("fieldslip", null, contentValues);
                    ContentValues contentValues1 = new ContentValues();
                    lastserialnumber=fieldslipnumber%1000;
                    contentValues1.put("lastserialnumber", lastserialnumber);
                    ret=db.update("todsliplist", contentValues1, "seasoncode = ? and todslipnumber = ?", new String[] { Integer.toString(seasoncode),Integer.toString(todslipnumber)});
                }
                catch (Exception e)
                {
                    //System.out.println("Error " + e.getMessage());
                    ret=-1;
                }
                res.close();
                db.close();
                return ret;
            }
            else
            {
                res.close();
                db.close();
                return 0;
            }
        }

    public long updateFieldsliplist (Integer seasoncode, Integer todslipnumber,Integer fieldslipnumber,Integer tyregadicode,Integer containercode,Integer layercode)
    {
        SQLiteDatabase db = this.getWritableDatabase();
        long ret;
        int lastserialnumber;
        Cursor res =  db.rawQuery( "select t.*,l.vehiclecategorycode from todsliplist t,todslipvehiclelist l where t.seasoncode=l.seasoncode and t.todslipnumber=l.todslipnumber and t.seasoncode="+Integer.toString(seasoncode)+ " and t.todslipnumber="+Integer.toString(todslipnumber)+" and (l.vehiclecode="+Integer.toString(tyregadicode)+" or l.tyregadicode="+Integer.toString(tyregadicode)+")", null );
        if (res.getCount() >0)
        {
            res.moveToFirst();
            ContentValues contentValues = new ContentValues();
            contentValues.put("seasoncode", seasoncode);
            contentValues.put("fieldslipnumber", fieldslipnumber);
            contentValues.put("fieldslipdate", getDateTime());
            contentValues.put("plotnumber", res.getInt(res.getColumnIndex("plotnumber")));
            contentValues.put("farmercategorycode", res.getInt(res.getColumnIndex("farmercategorycode")));
            contentValues.put("villagecode", res.getInt(res.getColumnIndex("villagecode")));
                /*if(res.isNull(res.getInt(res.getColumnIndex("subvillagecode")))==false) {
                    contentValues.put("subvillagecode", res.getInt(res.getColumnIndex("subvillagecode")));
                }*/
            contentValues.put("vehiclecategorycode", res.getInt(res.getColumnIndex("vehiclecategorycode")));
            if (res.getInt(res.getColumnIndex("vehiclecategorycode"))==3)
            {
                contentValues.put("tyregadicode", tyregadicode);
            }
            else
            {
                contentValues.put("vehiclecode", tyregadicode);
            }
            contentValues.put("hrsubcontractorcode", res.getInt(res.getColumnIndex("hrsubcontractorcode")));
            contentValues.put("hrtrsubcontractorcode", res.getInt(res.getColumnIndex("hrtrsubcontractorcode")));
            contentValues.put("trsubcontractorcode", res.getInt(res.getColumnIndex("trsubcontractorcode")));
            contentValues.put("caneconditioncode", res.getInt(res.getColumnIndex("caneconditioncode")));
            contentValues.put("slipboycode", res.getInt(res.getColumnIndex("slipboycode")));
            //contentValues.put("distance", res.getInt(res.getColumnIndex("distance")));
            contentValues.put("layercode", layercode);
            contentValues.put("containercode", containercode);
            contentValues.put("todslipnumber", todslipnumber);
            //contentValues.put("viadistance", res.getInt(res.getColumnIndex("viadistance")));
            ret=0;
            try
            {
                ret=db.update("fieldslip",contentValues ,"seasoncode = ? and fieldslipnumber = ?", new String[] { Integer.toString(seasoncode),Integer.toString(fieldslipnumber)});
                //ContentValues contentValues1 = new ContentValues();
                //lastserialnumber=fieldslipnumber%1000;
                //contentValues1.put("lastserialnumber", lastserialnumber);
                //ret=db.update("todsliplist", contentValues1, "seasoncode = ? and todslipnumber = ?", new String[] { Integer.toString(seasoncode),Integer.toString(todslipnumber)});
            }
            catch (Exception e)
            {
                //System.out.println("Error " + e.getMessage());
                ret=-1;
            }
            res.close();
            db.close();
            return ret;
        }
        else
        {
            res.close();
            db.close();
            return 0;
        }
    }

        public long updatePlotselfie(Integer seasoncode, Integer plotnumber, byte[] byteImage)
        {
            SQLiteDatabase db = this.getWritableDatabase();
            ContentValues contentValues = new ContentValues();
            contentValues.put("seasoncode", seasoncode);
            contentValues.put("plotnumber", plotnumber);
            contentValues.put("selfie",byteImage);
            return db.update("plotlist", contentValues, "seasoncode = ? and plotnumber = ?", new String[] { Integer.toString(seasoncode),Integer.toString(plotnumber)});

        }
        public long updatePlotidproof(Integer seasoncode, Integer plotnumber, byte[] byteImage)
        {
            SQLiteDatabase db = this.getWritableDatabase();
            ContentValues contentValues = new ContentValues();
            contentValues.put("seasoncode", seasoncode);
            contentValues.put("plotnumber", plotnumber);
            contentValues.put("aadhar",byteImage);
            return db.update("plotlist", contentValues, "seasoncode = ? and plotnumber = ?", new String[] { Integer.toString(seasoncode),Integer.toString(plotnumber)});

        }
        public long updatePlotpassbook(Integer seasoncode, Integer plotnumber, byte[] byteImage)
        {
            SQLiteDatabase db = this.getWritableDatabase();
            ContentValues contentValues = new ContentValues();
            contentValues.put("seasoncode", seasoncode);
            contentValues.put("plotnumber", plotnumber);
            contentValues.put("passbook",byteImage);
            return db.update("plotlist", contentValues, "seasoncode = ? and plotnumber = ?", new String[] { Integer.toString(seasoncode),Integer.toString(plotnumber)});

        }
        public byte[] getPlotselfie(Integer seasoncode, Integer plotnumber)
        {
            SQLiteDatabase db = this.getReadableDatabase();
            Cursor res =  db.rawQuery( "select selfie from plotlist where seasoncode="+Integer.toString(seasoncode)+ " and plotnumber="+Integer.toString(plotnumber), null );
            if (res.getCount() >0)
            {
                res.moveToFirst();
                byte[] blob = res.getBlob(res.getColumnIndex("selfie"));
                res.close();
                db.close();
                return blob;
            }
            else
            {
                res.close();
                db.close();
                return null;
            }
        }
        public byte[] getPlotidproof(Integer seasoncode, Integer plotnumber)
        {
            SQLiteDatabase db = this.getReadableDatabase();
            Cursor res =  db.rawQuery( "select aadhar from plotlist where seasoncode="+Integer.toString(seasoncode)+ " and plotnumber="+Integer.toString(plotnumber), null );
            if (res.getCount() >0)
            {
                res.moveToFirst();
                byte[] blob = res.getBlob(res.getColumnIndex("aadhar"));
                res.close();
                db.close();
                return blob;
            }
            else
            {
                res.close();
                db.close();
                return null;
            }
        }
        public byte[] getPlotpassbook(Integer seasoncode, Integer plotnumber)
        {
            SQLiteDatabase db = this.getReadableDatabase();
            Cursor res =  db.rawQuery( "select passbook from plotlist where seasoncode="+Integer.toString(seasoncode)+ " and plotnumber="+Integer.toString(plotnumber), null );
            if (res.getCount() >0)
            {
                res.moveToFirst();
                byte[] blob = res.getBlob(res.getColumnIndex("passbook"));
                res.close();
                db.close();
                return blob;
            }
            else
            {
                res.close();
                db.close();
                return null;
            }
        }
        public boolean insertPLotareadetail (Integer seasoncode, Integer plotnumber, Integer serialnumber,Double latitude,Double longitude) {
            SQLiteDatabase db = this.getWritableDatabase();
            ContentValues contentValues = new ContentValues();
            contentValues.put("seasoncode", seasoncode);
            contentValues.put("plotnumber", plotnumber);
            contentValues.put("serialnumber", serialnumber);
            contentValues.put("latitude", latitude);
            contentValues.put("longitude", longitude);
            db.insert("plotareadetail", null, contentValues);
            db.close();
            return true;
        }

        public Cursor getData(int id) {
            SQLiteDatabase db = this.getReadableDatabase();
            Cursor res =  db.rawQuery( "select * from plotlist where id="+id+"", null );
            return res;
        }
        public Cursor getDataareadetail(int id) {
            SQLiteDatabase db = this.getReadableDatabase();
            Cursor res =  db.rawQuery( "select * from plotareadetail where id="+id+"", null );
            return res;
        }
        public int numberOfRows(){
            SQLiteDatabase db = this.getReadableDatabase();
            int numRows = (int) DatabaseUtils.queryNumEntries(db, PLOTLIST_TABLE_NAME);
            db.close();
            return numRows;
        }
        public int numberOfRowsareadetail(){
            SQLiteDatabase db = this.getReadableDatabase();
            int numRows = (int) DatabaseUtils.queryNumEntries(db, PLOTAREADETAIL_TABLE_NAME);
            db.close();
            return numRows;
        }

    public Integer getPointCount(Integer seasoncode, Integer plotnumber)
    {
        Integer id;
        SQLiteDatabase db = this.getReadableDatabase();
        Cursor cursor = db.rawQuery("select count(*) cnt " +
                "from plotareadetail d " +
                "WHERE d.seasoncode= " + seasoncode + " and d.plotnumber = " + plotnumber, null);
        if (cursor != null) {
            cursor.moveToFirst();
            id = cursor.getInt(0);
        } else {
            id=0;
        }
        return id;
    }



        public boolean updatePlotlist (Integer seasoncode, Integer plotnumber, Double area,String remark) {
            byte[] selfie,passbook, idproof;
            String query="";
            Integer selfiecount=0,pbcount=0,idcount=0,pointcount=0;
            selfie=getPlotselfie(seasoncode,plotnumber);
            passbook=getPlotpassbook(seasoncode,plotnumber);
            idproof=getPlotidproof(seasoncode,plotnumber);
            if (selfie == null)
                selfiecount = 0;
            else
                selfiecount = 1;
            if (passbook == null)
                pbcount = 0;
            else
                pbcount = 1;
            if (idproof == null)
                idcount = 0;
            else
                idcount = 1;
            pointcount = getPointCount(seasoncode,plotnumber);
            ContentValues contentValues = new ContentValues();
            contentValues.put("area",area);
            contentValues.put("remark", remark);
            contentValues.put("pointcount",pointcount);
            contentValues.put("selfiecount",selfiecount);
            contentValues.put("idcount",idcount);
            contentValues.put("pbcount",pbcount);
            SQLiteDatabase db = this.getWritableDatabase();
            db.update("plotlist", contentValues, "seasoncode = ? and plotnumber = ?", new String[] { Integer.toString(seasoncode),Integer.toString(plotnumber)});
            db.close();
            return true;
        }

        public Integer deletePlotlist (Integer id)
        {
            SQLiteDatabase db = this.getWritableDatabase();
            return db.delete("plotlist",
                    "id = ? ",
                    new String[] { Integer.toString(id) });
        }
        public Integer deletePlot (Integer seasoncode, Integer plotnumber)
        {
            SQLiteDatabase db = this.getWritableDatabase();
            return db.delete("plotlist",
                    "seasoncode = ? and plotnumber = ?",
                    new String[] { Integer.toString(seasoncode),Integer.toString(plotnumber)});

        }
        public Integer deletePlotareadetail (Integer seasoncode, Integer plotnumber,Integer serialnumber)
        {
            SQLiteDatabase db = this.getWritableDatabase();
            return db.delete("plotareadetail",
                    "seasoncode = ? and plotnumber = ? and serialnumber = ?",
                    new String[] { Integer.toString(seasoncode),Integer.toString(plotnumber), Integer.toString(serialnumber)});

        }
        public Integer deleteUploadedplots (Integer seasoncode, Integer plotnumber)
        {
            SQLiteDatabase db = this.getWritableDatabase();
            db.execSQL("PRAGMA foreign_keys=ON");
            return db.delete("plotlist",
                    "area is not null and seasoncode=" + seasoncode + " and plotnumber=" + plotnumber,null );
        }
        public Integer deleteUploadedfieldslips (Integer vehiclecode)
        {
            SQLiteDatabase db = this.getWritableDatabase();
            //db.execSQL("PRAGMA foreign_keys=ON");
            return db.delete("fieldslip",
                    "vehiclecode = ? or tyregadicode = ?",
                    new String[] { Integer.toString(vehiclecode),Integer.toString(vehiclecode)});
        }
        public Integer deleteAllfieldslips ()
        {
            SQLiteDatabase db = this.getWritableDatabase();
            //db.execSQL("PRAGMA foreign_keys=ON");
            return db.delete("fieldslip",
                    "1 = ? ",
                    new String[] { Integer.toString(1)});
        }

        public Integer deleteAlltodslipvehiclelist()
        {
            SQLiteDatabase db = this.getWritableDatabase();
            //db.execSQL("PRAGMA foreign_keys=ON");
            return db.delete("todslipvehiclelist",
                    "1 = ? ",
                    new String[] { Integer.toString(1)});
        }

    public Integer deleteAlltodsliplist()
    {
        SQLiteDatabase db = this.getWritableDatabase();
        //db.execSQL("PRAGMA foreign_keys=ON");
        return db.delete("todsliplist",
                "1 = ? ",
                new String[] { Integer.toString(1)});
    }

        public ArrayList<PlotList> getPendingplotlist()
        {
            ArrayList<PlotList> array_list = new ArrayList<PlotList>();
            Integer i=0;

            //hp = new HashMap();
            SQLiteDatabase db = this.getReadableDatabase();
            Cursor res =  db.rawQuery( "select seasoncode,plotnumber,villagename,farmername,gatsurveno,inareaoutarea,variety from plotlist WHERE area is null order by villagename,farmername,gatsurveno;", null );
            if (res.getCount() >0) {
                res.moveToFirst();
                while (res.isAfterLast() == false) {
                    PlotList plot1 = new PlotList();
                    plot1.setSeasoncode(res.getInt(res.getColumnIndex("seasoncode")));
                    plot1.setPlotnumber(res.getInt(res.getColumnIndex("plotnumber")));
                    plot1.setVillagename(res.getString(res.getColumnIndex("villagename")));
                    plot1.setFarmername(res.getString(res.getColumnIndex("farmername")));
                    plot1.setGatsurveno(res.getString(res.getColumnIndex("gatsurveno")));
                    plot1.setInareaoutarea(res.getInt(res.getColumnIndex("inareaoutarea")));
                    plot1.setVariety(res.getString(res.getColumnIndex("variety")));
                    array_list.add(plot1);
                    //array_list.add(res.getString(res.getColumnIndex(PLOTLIST_COLUMN_NAME)));
                    res.moveToNext();
                    i++;
                }
            }
            res.close();
            db.close();
            return array_list;
        }

        public ArrayList<TodslipList> getPendingtodsliplist()
        {
            ArrayList<TodslipList> array_list = new ArrayList<TodslipList>();
            Integer i=0;

            //hp = new HashMap();
            SQLiteDatabase db = this.getReadableDatabase();
            Cursor res =  db.rawQuery( "select seasoncode,todslipnumber,plotnumber,villagenameuni,farmernameuni,gatsurveno,harvesternameuni,transporternameuni,harvestertransporternameuni,caneconditioncode,lastserialnumber from todsliplist WHERE iscompleted is null order by villagenameuni,farmernameuni,gatsurveno;", null );
            if (res.getCount() >0) {
                res.moveToFirst();
                while (res.isAfterLast() == false) {
                    TodslipList plot1 = new TodslipList();
                    plot1.setSeasoncode(res.getInt(res.getColumnIndex("seasoncode")));
                    plot1.setTodslipnumber(res.getInt(res.getColumnIndex("todslipnumber")));
                    plot1.setPlotnumber(res.getInt(res.getColumnIndex("plotnumber")));
                    plot1.setVillagenameuni(res.getString(res.getColumnIndex("villagenameuni")));
                    plot1.setFarmernameuni(res.getString(res.getColumnIndex("farmernameuni")));
                    plot1.setGatsurveno(res.getString(res.getColumnIndex("gatsurveno")));
                    plot1.setHarvesternameuni(res.getString(res.getColumnIndex("harvesternameuni")));
                    plot1.setTransporternameuni(res.getString(res.getColumnIndex("transporternameuni")));
                    plot1.setHarvestertransporternameuni(res.getString(res.getColumnIndex("harvestertransporternameuni")));
                    plot1.setCaneconditioncode(res.getInt(res.getColumnIndex("caneconditioncode")));
                    plot1.setLastserialnumber(res.getInt(res.getColumnIndex("lastserialnumber")));
                    array_list.add(plot1);
                    //array_list.add(res.getString(res.getColumnIndex(PLOTLIST_COLUMN_NAME)));
                    res.moveToNext();
                    i++;
                }
            }
            res.close();
            db.close();
            return array_list;
        }

    public Integer getVehiclecategorybytodslip(Integer seasoncode,Integer todslipnumber)
    {
        ArrayList<TodslipList> array_list = new ArrayList<TodslipList>();
        Integer i=0,vehcatcode=0;

        //hp = new HashMap();
        SQLiteDatabase db = this.getReadableDatabase();
        Cursor res =  db.rawQuery( "select v.vehiclecategorycode from todsliplist t,todslipvehiclelist v " +
                "WHERE t.seasoncode=v.seasoncode and t.todslipnumber=v.todslipnumber " +
                "and t.seasoncode="+seasoncode.toString()+" and t.todslipnumber="+todslipnumber.toString()+";", null );
        if (res.getCount() >0) {
            res.moveToFirst();
            while (res.isAfterLast() == false) {
                TodslipList plot1 = new TodslipList();
                vehcatcode=res.getInt(res.getColumnIndex("vehiclecategorycode"));
                //array_list.add(res.getString(res.getColumnIndex(PLOTLIST_COLUMN_NAME)));
                break;
            }
        }
        res.close();
        db.close();
        return vehcatcode;
    }


    public ArrayList<PlotList> getCompletedplotlist()
        {
            ArrayList<PlotList> array_list = new ArrayList<PlotList>();
            Integer i=0;

            //hp = new HashMap();
            SQLiteDatabase db = this.getReadableDatabase();
            Cursor res =  db.rawQuery( "select seasoncode,plotnumber,villagename,farmername,gatsurveno,inareaoutarea,variety from plotlist WHERE area is not null order by villagename,farmername,gatsurveno;", null );
            if (res.getCount() >0) {
                res.moveToFirst();
                while (res.isAfterLast() == false) {
                    PlotList plot1 = new PlotList();
                    plot1.setSeasoncode(res.getInt(res.getColumnIndex("seasoncode")));
                    plot1.setPlotnumber(res.getInt(res.getColumnIndex("plotnumber")));
                    plot1.setVillagename(res.getString(res.getColumnIndex("villagename")));
                    plot1.setFarmername(res.getString(res.getColumnIndex("farmername")));
                    plot1.setGatsurveno(res.getString(res.getColumnIndex("gatsurveno")));
                    plot1.setInareaoutarea(res.getInt(res.getColumnIndex("inareaoutarea")));
                    plot1.setVariety(res.getString(res.getColumnIndex("variety")));
                    array_list.add(plot1);
                    //array_list.add(res.getString(res.getColumnIndex(PLOTLIST_COLUMN_NAME)));
                    res.moveToNext();
                    i++;
                }
            }
            res.close();
            db.close();
            return array_list;
        }
    public ArrayList<Vehiclefieldsliplist> getVehiclefieldsliplist()
    {
        ArrayList<Vehiclefieldsliplist> array_list = new ArrayList<Vehiclefieldsliplist>();
        Integer i=0;
        String sql="select vehiclecode,vehiclenumber,vehiclecategorycode" +
                ",(select count(*) as cnt from fieldslip f where (f.vehiclecode=t.vehiclecode or f.tyregadicode=t.vehiclecode)) as slipcount\n" +
                ",case when vehiclecategorycode=1 then 'ट्रक'  \n" +
                "when vehiclecategorycode=2 then 'ट्रॅक्टर'\n" +
                "when vehiclecategorycode=3 then 'टायरगाडी'\n" +
                "when vehiclecategorycode=4 then 'जुगाड'\n" +
                "end as vehiclecategory\n" +
                "from \n" +
                "(\n" +
                "select vehiclecode,(select v.vehiclenumber from todslipvehiclelist v where v.vehiclecode=f1.vehiclecode limit 1) as vehiclenumber\n" +
                ",(select v.vehiclecategorycode from todslipvehiclelist v where v.vehiclecode=f1.vehiclecode limit 1) as vehiclecategorycode\n" +
                "from fieldslip f1\n" +
                "where vehiclecode is not null\n" +
                "group by vehiclecode\n" +
                "union all\n" +
                "select tyregadicode,(select w.tyregadinumber from todslipvehiclelist w where w.tyregadicode=f2.tyregadicode limit 1),3 as vehiclecategorycode\n" +
                "from fieldslip f2\n" +
                "where tyregadicode is not null\n" +
                "group by tyregadicode)t;";
        //hp = new HashMap();
        SQLiteDatabase db = this.getReadableDatabase();
        Cursor res =  db.rawQuery( "select vehiclecode,vehiclenumber,vehiclecategorycode" +
                ",(select count(*) as cnt from fieldslip f where (f.vehiclecode=t.vehiclecode or f.tyregadicode=t.vehiclecode)) as slipcount\n" +
                ",case when vehiclecategorycode=1 then 'ट्रक'  \n" +
                "when vehiclecategorycode=2 then 'ट्रॅक्टर'\n" +
                "when vehiclecategorycode=3 then 'टायरगाडी'\n" +
                "when vehiclecategorycode=4 then 'जुगाड'\n" +
                "end as vehiclecategory\n" +
                "from \n" +
                "(\n" +
                "select vehiclecode,(select v.vehiclenumber from todslipvehiclelist v where v.vehiclecode=f1.vehiclecode limit 1) as vehiclenumber\n" +
                ",(select v.vehiclecategorycode from todslipvehiclelist v where v.vehiclecode=f1.vehiclecode limit 1) as vehiclecategorycode\n" +
                "from fieldslip f1\n" +
                "where vehiclecode is not null\n" +
                "group by vehiclecode\n" +
                "union all\n" +
                "select tyregadicode,(select w.tyregadinumber from todslipvehiclelist w where w.tyregadicode=f2.tyregadicode limit 1),3 as vehiclecategorycode\n" +
                "from fieldslip f2\n" +
                "where tyregadicode is not null\n" +
                "group by tyregadicode)t;", null );
        if (res.getCount() >0) {
            res.moveToFirst();
            while (res.isAfterLast() == false)
            {
                Vehiclefieldsliplist vehicle1 = new Vehiclefieldsliplist();
                vehicle1.setVehiclecategory(res.getString(res.getColumnIndex("vehiclecategory")));
                vehicle1.setVehiclecode(res.getInt(res.getColumnIndex("vehiclecode")));
                vehicle1.setVehiclenumber(res.getString(res.getColumnIndex("vehiclenumber")));
                vehicle1.setVehiclecategorycode(res.getInt(res.getColumnIndex("vehiclecategorycode")));
                vehicle1.setSlipcount(res.getInt(res.getColumnIndex("slipcount")));
                array_list.add(vehicle1);
                //array_list.add(res.getString(res.getColumnIndex(PLOTLIST_COLUMN_NAME)));
                res.moveToNext();
                i++;
            }
        }
        res.close();
        db.close();
        return array_list;
    }

    public ArrayList<TodslipList> getFieldslip(Integer seasoncode, Integer fieldslipnumber)
    {
        ArrayList<TodslipList> array_list = new ArrayList<TodslipList>();
        Integer i=0;
        //hp = new HashMap();
        SQLiteDatabase db = this.getReadableDatabase();
        Cursor res =  db.rawQuery( "select t.seasoncode,t.todslipnumber,t.plotnumber,t.farmernameuni,t.villagenameuni,t.gatsurveno\n" +
                ",t.harvesternameuni,t.transporternameuni,t.harvestertransporternameuni\n" +
                ",f.caneconditioncode,f.fieldslipnumber%1000 as lastserialnumber\n" +
                ",f.containercode,f.layercode,f.vehiclecode,f.tyregadicode\n" +
                "from fieldslip f,todsliplist t\n" +
                "where f.seasoncode=t.seasoncode and f.todslipnumber=t.todslipnumber \n" +
                "and f.seasoncode="+seasoncode.toString()+" and f.fieldslipnumber="+fieldslipnumber.toString()+";", null );
        if (res.getCount() >0) {
            res.moveToFirst();
            while (res.isAfterLast() == false) {
                TodslipList fieldslip1 = new TodslipList();
                fieldslip1.setSeasoncode(res.getInt(res.getColumnIndex("seasoncode")));
                fieldslip1.setTodslipnumber(res.getInt(res.getColumnIndex("todslipnumber")));
                fieldslip1.setPlotnumber(res.getInt(res.getColumnIndex("plotnumber")));
                fieldslip1.setVillagenameuni(res.getString(res.getColumnIndex("villagenameuni")));
                fieldslip1.setFarmernameuni(res.getString(res.getColumnIndex("farmernameuni")));
                fieldslip1.setGatsurveno(res.getString(res.getColumnIndex("gatsurveno")));
                fieldslip1.setHarvesternameuni(res.getString(res.getColumnIndex("harvesternameuni")));
                fieldslip1.setTransporternameuni(res.getString(res.getColumnIndex("transporternameuni")));
                fieldslip1.setHarvestertransporternameuni(res.getString(res.getColumnIndex("harvestertransporternameuni")));
                fieldslip1.setCaneconditioncode(res.getInt(res.getColumnIndex("caneconditioncode")));
                fieldslip1.setLastserialnumber(res.getInt(res.getColumnIndex("lastserialnumber")));

                fieldslip1.setContainercode(res.getInt(res.getColumnIndex("containercode")));
                fieldslip1.setLayercode(res.getInt(res.getColumnIndex("layercode")));
                if (res.isNull(res.getColumnIndex("vehiclecode")))
                {
                    fieldslip1.setVehiclecode(res.getInt(res.getColumnIndex("tyregadicode")));
                }
                else
                {
                    fieldslip1.setVehiclecode(res.getInt(res.getColumnIndex("vehiclecode")));
                }
                array_list.add(fieldslip1);
                //array_list.add(res.getString(res.getColumnIndex(PLOTLIST_COLUMN_NAME)));
                res.moveToNext();
                i++;
            }
        }
        res.close();
        db.close();
        return array_list;
    }


    public ArrayList<Fieldsliplistbyvehiclecode> getFieldsliplist(Integer vehiclecode)
    {
        ArrayList<Fieldsliplistbyvehiclecode> array_list = new ArrayList<Fieldsliplistbyvehiclecode>();
        Integer i=0;

        //hp = new HashMap();
        SQLiteDatabase db = this.getReadableDatabase();
        Cursor res =  db.rawQuery( "select f.seasoncode,f.fieldslipnumber,t.farmercode,t.farmernameuni,f.plotnumber,f.containercode,f.layercode\n" +
                ",case when containercode=1 then 'पुढील ट्रेलर'\n" +
                "when containercode=2 then 'मागील ट्रेलर'\n" +
                "when containercode=3 then 'पुढील आणि मागील ट्रेलर'\n" +
                "end as containername\n" +
                ",case when layercode=1 then 'वरचा'\n" +
                "when layercode=2 then 'मधला'\n" +
                "when layercode=3 then 'खालचा'\n" +
                "when layercode=4 then 'पुढील संपुर्ण व मागील वरचा'\n" +
                "when layercode=5 then 'पुढील संपुर्ण व मागील खालचा'\n" +
                "when layercode=6 then 'पुढील वरचा व मागील संपुर्ण'\n" +
                "when layercode=7 then 'पुढील खालचा व मागील संपुर्ण'\n" +
                "when layercode=9 then 'संपुर्ण'\n" +
                "end as layername\n" +
                "from fieldslip f,todsliplist t\n" +
                "where f.seasoncode=t.seasoncode and f.todslipnumber=t.todslipnumber \n" +
                "and (f.vehiclecode="+vehiclecode+" or f.tyregadicode="+vehiclecode+");", null );
        if (res.getCount() >0) {
            res.moveToFirst();
            while (res.isAfterLast() == false)
            {
                Fieldsliplistbyvehiclecode fieldslip1 = new Fieldsliplistbyvehiclecode();
                fieldslip1.setseasoncode(res.getInt(res.getColumnIndex("seasoncode")));
                fieldslip1.setfieldslipnumber(res.getInt(res.getColumnIndex("fieldslipnumber")));
                fieldslip1.setfarmercode(res.getInt(res.getColumnIndex("farmercode")));
                fieldslip1.setfarmername(res.getString(res.getColumnIndex("farmernameuni")));
                fieldslip1.setplotnumber(res.getInt(res.getColumnIndex("plotnumber")));
                fieldslip1.setcontainercode(res.getInt(res.getColumnIndex("containercode")));
                fieldslip1.setcontainername(res.getString(res.getColumnIndex("containername")));
                fieldslip1.setlayercode(res.getInt(res.getColumnIndex("layercode")));
                fieldslip1.setlayername(res.getString(res.getColumnIndex("layername")));
                array_list.add(fieldslip1);
                //array_list.add(res.getString(res.getColumnIndex(PLOTLIST_COLUMN_NAME)));
                res.moveToNext();
                i++;
            }
        }
        res.close();
        db.close();
        return array_list;
    }

    public String getFieldsliplistverified(Integer vehiclecode)
    {
        Boolean result=false;
        Integer i=0, container1_layer1=0,container1_layer2=0,container1_layer3=0,container1_layer4=0,container1_layer5=0,container1_layer6=0,container1_layer7=0,container1_layer9=0;
        Integer container2_layer1=0,container2_layer2=0,container2_layer3=0,container2_layer4=0,container2_layer5=0,container2_layer6=0,container2_layer7=0,container2_layer9=0;
        Integer container3_layer4=0,container3_layer5=0,container3_layer6=0,container3_layer7=0,container3_layer9=0;
        Integer vehiclecategorycode=0;
        String sequence;
        //hp = new HashMap();
        SQLiteDatabase db = this.getReadableDatabase();
        Cursor res =  db.rawQuery( "select f.vehiclecategorycode,f.fieldslipnumber,f.containercode,f.layercode\n" +
                "from fieldslip f,todsliplist t\n" +
                "where f.seasoncode=t.seasoncode and f.todslipnumber=t.todslipnumber \n" +
                "and (f.vehiclecode="+vehiclecode+" or f.tyregadicode="+vehiclecode+");", null );
        if (res.getCount() >0) {
            res.moveToFirst();
            while (res.isAfterLast() == false)
            {
                vehiclecategorycode = res.getInt(res.getColumnIndex("vehiclecategorycode"));
                if (res.getInt(res.getColumnIndex("containercode")) == 1)
                {
                    if (res.getInt(res.getColumnIndex("layercode")) == 1 && container1_layer1 == 0 )
                    {
                        container1_layer1 = res.getInt(res.getColumnIndex("fieldslipnumber"));
                    }
                    else if (res.getInt(res.getColumnIndex("layercode")) == 1 && container1_layer1 != 0 )
                    {
                        container1_layer1 = -1;
                    }
                    else if (res.getInt(res.getColumnIndex("layercode")) == 2 && container1_layer2 == 0 )
                    {
                        container1_layer2 = res.getInt(res.getColumnIndex("fieldslipnumber"));
                    }
                    else if (res.getInt(res.getColumnIndex("layercode")) == 2 && container1_layer2 != 0 )
                    {
                        container1_layer2 = -1;
                    }
                    else if (res.getInt(res.getColumnIndex("layercode")) == 3 && container1_layer3 == 0 )
                    {
                        container1_layer3 = res.getInt(res.getColumnIndex("fieldslipnumber"));
                    }
                    else if (res.getInt(res.getColumnIndex("layercode")) == 3 && container1_layer3 != 0 )
                    {
                        container1_layer3 = -1;
                    }
                    else if (res.getInt(res.getColumnIndex("layercode")) == 4)
                    {
                        container1_layer4 = -1;
                    }
                    else if (res.getInt(res.getColumnIndex("layercode")) == 5)
                    {
                        container1_layer5 = -1;
                    }
                    else if (res.getInt(res.getColumnIndex("layercode")) == 6)
                    {
                        container1_layer6 = -1;
                    }
                    else if (res.getInt(res.getColumnIndex("layercode")) == 7)
                    {
                        container1_layer7 = -1;
                    }
                    else if (res.getInt(res.getColumnIndex("layercode")) == 9 && container1_layer9 == 0 )
                    {
                        container1_layer9 = res.getInt(res.getColumnIndex("fieldslipnumber"));
                    }
                    else if (res.getInt(res.getColumnIndex("layercode")) == 9 && container1_layer9 != 0 )
                    {
                        container1_layer9 = -1;
                    }
                }
                else if (res.getInt(res.getColumnIndex("containercode")) == 2)
                {
                    if (res.getInt(res.getColumnIndex("layercode")) == 1 && container2_layer1 == 0 )
                    {
                        container2_layer1 = res.getInt(res.getColumnIndex("fieldslipnumber"));
                    }
                    else if (res.getInt(res.getColumnIndex("layercode")) == 1 && container2_layer1 != 0 )
                    {
                        container2_layer1 = -1;
                    }
                    else if (res.getInt(res.getColumnIndex("layercode")) == 2 && container2_layer2 == 0 )
                    {
                        container2_layer2 = res.getInt(res.getColumnIndex("fieldslipnumber"));
                    }
                    else if (res.getInt(res.getColumnIndex("layercode")) == 2 && container2_layer2 != 0 )
                    {
                        container2_layer2 = -1;
                    }
                    else if (res.getInt(res.getColumnIndex("layercode")) == 3 && container2_layer3 == 0 )
                    {
                        container2_layer3 = res.getInt(res.getColumnIndex("fieldslipnumber"));
                    }
                    else if (res.getInt(res.getColumnIndex("layercode")) == 3 && container2_layer3 != 0 )
                    {
                        container2_layer3 = -1;
                    }
                    else if (res.getInt(res.getColumnIndex("layercode")) == 4)
                    {
                        container2_layer4 = -1;
                    }
                    else if (res.getInt(res.getColumnIndex("layercode")) == 5)
                    {
                        container2_layer5 = -1;
                    }
                    else if (res.getInt(res.getColumnIndex("layercode")) == 6)
                    {
                        container2_layer6 = -1;
                    }
                    else if (res.getInt(res.getColumnIndex("layercode")) == 7)
                    {
                        container2_layer7 = -1;
                    }
                    else if (res.getInt(res.getColumnIndex("layercode")) == 9 && container2_layer9 == 0 )
                    {
                        container2_layer9 = res.getInt(res.getColumnIndex("fieldslipnumber"));
                    }
                    else if (res.getInt(res.getColumnIndex("layercode")) == 9 && container2_layer9 != 0 )
                    {
                        container2_layer9 = -1;
                    }
                }
                else if (res.getInt(res.getColumnIndex("containercode")) == 3)
                {
                    if (res.getInt(res.getColumnIndex("layercode")) == 4 && container3_layer4 == 0)
                    {
                        container3_layer4 = res.getInt(res.getColumnIndex("fieldslipnumber"));
                    }
                    else if (res.getInt(res.getColumnIndex("layercode")) == 4 && container3_layer4 != 0)
                    {
                        container3_layer4 = -1;
                    }
                    else if (res.getInt(res.getColumnIndex("layercode")) == 5 && container3_layer5 == 0)
                    {
                        container3_layer5 = res.getInt(res.getColumnIndex("fieldslipnumber"));
                    }
                    else if (res.getInt(res.getColumnIndex("layercode")) == 5 && container3_layer5 != 0)
                    {
                        container3_layer5 = -1;
                    }
                    else if (res.getInt(res.getColumnIndex("layercode")) == 6 && container3_layer6 == 0)
                    {
                        container3_layer6 = res.getInt(res.getColumnIndex("fieldslipnumber"));
                    }
                    else if (res.getInt(res.getColumnIndex("layercode")) == 6 && container3_layer6 != 0)
                    {
                        container3_layer6 = -1;
                    }
                    else if (res.getInt(res.getColumnIndex("layercode")) == 7 && container3_layer7 == 0)
                    {
                        container3_layer7 = res.getInt(res.getColumnIndex("fieldslipnumber"));
                    }
                    else if (res.getInt(res.getColumnIndex("layercode")) == 7 && container3_layer7 != 0)
                    {
                        container3_layer7= -1;
                    }
                    else if (res.getInt(res.getColumnIndex("layercode")) == 9 && container3_layer9 == 0 )
                    {
                        container3_layer9 = res.getInt(res.getColumnIndex("fieldslipnumber"));
                    }
                    else if (res.getInt(res.getColumnIndex("layercode")) == 9 && container3_layer9 != 0 )
                    {
                        container3_layer9 = -1;
                    }
                }
                res.moveToNext();
                i++;
            }
        }
        // check for any double layer or wrong layer
        if (container1_layer1 == -1 || container1_layer2 == -1 || container1_layer3 == -1 ||container1_layer4 == -1 || container1_layer5 == -1 || container1_layer6 == -1 || container1_layer7 == -1 || container1_layer9 == -1 || container2_layer1 == -1 || container2_layer2 == -1 || container2_layer3 == -1 ||container2_layer4 == -1 || container2_layer5 == -1 || container2_layer6 == -1 || container2_layer7 == -1|| container2_layer9 == -1 ||container3_layer4 == -1 || container3_layer5 == -1 || container3_layer6 == -1 || container3_layer7 == -1|| container3_layer9 == -1)
        {
            result=false;
            sequence="-1";
        }
        // check for truck and bulluckcart
        else if ((vehiclecategorycode == 1 || vehiclecategorycode == 3) && (container2_layer1 > 0 || container2_layer2 > 0 || container2_layer3 > 0 || container2_layer9 > 0 || container3_layer4 > 0 || container3_layer5 > 0 || container3_layer6 > 0 || container3_layer7 > 0 || container3_layer9 > 0))
        {
            result=false;
            sequence="-2";
        }
        // check for 1 - FULL and 2 - FULL
        else if (container1_layer1 == 0 && container1_layer2 == 0 && container1_layer3 == 0 && container1_layer9 == 0 && container2_layer1 == 0 && container2_layer2 == 0 && container2_layer3 == 0 && container2_layer9 == 0 && container3_layer4 == 0 && container3_layer5 == 0 && container3_layer6 == 0 && container3_layer7 == 0 && container3_layer9 > 0 )
        {
            result=true;
            sequence="*1-" + container3_layer9 + "#1F2F@";
        }
        // check for (1 - FULL and 2 - Top) and 2 - Bottom
        else if (container1_layer1 == 0 && container1_layer2 == 0 && container1_layer3 == 0 && container1_layer9 == 0 && container2_layer1 == 0 && container2_layer2 == 0 && container2_layer3 > 0 && container2_layer9 == 0 && container3_layer4 > 0 && container3_layer5 == 0 && container3_layer6 == 0 && container3_layer7 == 0 && container3_layer9 == 0 )
        {
            result=true;
            sequence="*1-" + container3_layer4 + "#1F2T" + ",2-" + container2_layer3 + "#2B@";
        }
        // check for (1 - FULL and 2 - Top) and 2 - Middle and 2 - Bottom
        else if (container1_layer1 == 0 && container1_layer2 == 0 && container1_layer3 == 0 && container1_layer9 == 0 && container2_layer1 == 0 && container2_layer2 > 0 && container2_layer3 > 0 && container2_layer9 == 0 && container3_layer4 > 0 && container3_layer5 == 0 && container3_layer6 == 0 && container3_layer7 == 0 && container3_layer9 == 0 )
        {
            result=true;
            sequence="*1-" + container3_layer4 + "#1F2T"  + ",2-" + container2_layer2 + "#2M" + ",3-" + container2_layer3 + "#2B@";
        }
        // check for (1 - FULL and 2 - Bottom) and 2 - Top
        else if (container1_layer1 == 0 && container1_layer2 == 0 && container1_layer3 == 0 && container1_layer9 == 0 && container2_layer1 > 0 && container2_layer2 == 0 && container2_layer3 == 0 && container2_layer9 == 0 && container3_layer4 == 0 && container3_layer5 > 0 && container3_layer6 == 0 && container3_layer7 == 0 && container3_layer9 == 0 )
        {
            result=true;
            sequence="*1-" + container2_layer1 + "#2T" + ",2-" + container3_layer5 + "#1F2B@";
        }
        // check for (1 - FULL and 2 - Bottom) and 2 - Top and 2 - Middle
        else if (container1_layer1 == 0 && container1_layer2 == 0 && container1_layer3 == 0 && container1_layer9 == 0 && container2_layer1 > 0 && container2_layer2 > 0 && container2_layer3 == 0 && container2_layer9 == 0 && container3_layer4 == 0 && container3_layer5 > 0 && container3_layer6 == 0 && container3_layer7 == 0 && container3_layer9 == 0 )
        {
            result=true;
            sequence="*1-" + container2_layer1 + "#2T" + ",2-" + container2_layer2 + "#2M" + ",3-" + container3_layer5 + "#1F2B@";
        }
        // check for (1 - Top and 2 - Full) and 1 - Bottom
        else if (container1_layer1 == 0 && container1_layer2 == 0 && container1_layer3 > 0 && container1_layer9 == 0 && container2_layer1 == 0 && container2_layer2 == 0 && container2_layer3 == 0 && container2_layer9 == 0 && container3_layer4 == 0 && container3_layer5 == 0 && container3_layer6 > 0 && container3_layer7 == 0 && container3_layer9 == 0 )
        {
            result=true;
            sequence="*1-" + container3_layer6 + "#1T2F" + ",2-" + container1_layer3 + "#1B@";
        }
        // check for (1 - Top and 2 - Full) and 1 - Middle and 1 - Bottom
        else if (container1_layer1 == 0 && container1_layer2 > 0 && container1_layer3 > 0 && container1_layer9 == 0 && container2_layer1 == 0 && container2_layer2 == 0 && container2_layer3 == 0 && container2_layer9 == 0 && container3_layer4 == 0 && container3_layer5 == 0 && container3_layer6 > 0 && container3_layer7 == 0 && container3_layer9 == 0 )
        {
            result=true;
            sequence="*1-" + container3_layer6 + "#1T2F" + ",2-" + container1_layer2 + "#1M" + ",3-" + container1_layer3 + "#1B@";
        }
        // check for (1 - Bottom and 2 - Full) and 1 - Top
        else if (container1_layer1 > 0 && container1_layer2 == 0 && container1_layer3 == 0 && container1_layer9 == 0 && container2_layer1 == 0 && container2_layer2 == 0 && container2_layer3 == 0 && container2_layer9 == 0 && container3_layer4 == 0 && container3_layer5 == 0 && container3_layer6 == 0 && container3_layer7 > 0 && container3_layer9 == 0 )
        {
            result=true;
            sequence="*1-" + container1_layer1 + "#1T" + ",2-" + container3_layer7 + "#1B2F@";
        }
        // check for (1 - Bottom and 2 - Full) and 1 - Top and 1 - Middle
        else if (container1_layer1 > 0 && container1_layer2 > 0 && container1_layer3 == 0 && container1_layer9 == 0 && container2_layer1 == 0 && container2_layer2 == 0 && container2_layer3 == 0 && container2_layer9 == 0 && container3_layer4 == 0 && container3_layer5 == 0 && container3_layer6 == 0 && container3_layer7 > 0 && container3_layer9 == 0 )
        {
            result=true;
            sequence="*1-" + container1_layer1 + "#1T" + ",2-" + container1_layer2 + "#1M" + ",3-" + container3_layer7 + "#1B2F@";
        }
        // check for 1 - FULL
        else if (container1_layer1 == 0 && container1_layer2 == 0 && container1_layer3 == 0 && container1_layer9 > 0 && container2_layer1 == 0 && container2_layer2 == 0 && container2_layer3 == 0 && container2_layer9 == 0 && container3_layer9 == 0 )
        {
            result=true;
            sequence="*1-" + container1_layer9 + "#1F@";
        }
        // check for 2 - FULL
        else if (container1_layer1 == 0 && container1_layer2 == 0 && container1_layer3 == 0 && container1_layer9 == 0 && container2_layer1 == 0 && container2_layer2 == 0 && container2_layer3 == 0 && container2_layer9 > 0 && container3_layer9 == 0 )
        {
            result=true;
            sequence="*1-" + container2_layer9 + "#2F@";
        }
        // check for 1 - Top - Bottom
        else if (container1_layer1 > 0 && container1_layer2 == 0 && container1_layer3 > 0 && container1_layer9 == 0 && container2_layer1 == 0 && container2_layer2 == 0 && container2_layer3 == 0 && container2_layer9 == 0 && container3_layer9 == 0 )
        {
            result=true;
            sequence="*1-" + container1_layer1 + "#1T" + ",2-" + container1_layer3 + "#1B@";
        }
        // check for 2 - Top - Bottom
        else if (container1_layer1 == 0 && container1_layer2 == 0 && container1_layer3 == 0 && container1_layer9 == 0 && container2_layer1 > 0 && container2_layer2 == 0 && container2_layer3 > 0 && container2_layer9 == 0 && container3_layer9 == 0 )
        {
            result=true;
            sequence="*1-" + container2_layer1  + "#2T" + ",2-" + container2_layer3  + "#2B@";
        }
        // check for 1 - Top - Middle - Bottom
        else if (container1_layer1 > 0 && container1_layer2 > 0 && container1_layer3 > 0 && container1_layer9 == 0 && container2_layer1 == 0 && container2_layer2 == 0 && container2_layer3 == 0 && container2_layer9 == 0 && container3_layer9 == 0 )
        {
            result=true;
            sequence="*1-" + container1_layer1  + "#1T" + ",2-" + container1_layer2  + "#1M" + ",3-" + container1_layer3  + "#1B@";
        }
        // check for 2 - Top - Middle - Bottom
        else if (container1_layer1 == 0 && container1_layer2 == 0 && container1_layer3 == 0 && container1_layer9 == 0 && container2_layer1 > 0 && container2_layer2 > 0 && container2_layer3 > 0 && container2_layer9 == 0 && container3_layer9 == 0 )
        {
            result=true;
            sequence="*1-" + container2_layer1  + "#2T" + ",2-" + container2_layer2  + "#1M" + ",3-" + container2_layer3  + "#2B@";
        }
        // check for 1 - FULL and 2 - Top - Bottom
        else if (container1_layer1 == 0 && container1_layer2 == 0 && container1_layer3 == 0 && container1_layer9 > 0 && container2_layer1 > 0 && container2_layer2 == 0 && container2_layer3 > 0 && container2_layer9 == 0 && container3_layer9 == 0 )
        {
            result=true;
            sequence="*1-" + container1_layer9  + "#1F" + ",2-"+ container2_layer1  + "#2T" + ",3-" + container2_layer3  + "#2B@";
        }
        // check for 2 - FULL and 1 - Top - Bottom
        else if (container1_layer1 > 0 && container1_layer2 == 0 && container1_layer3 > 0 && container1_layer9 == 0 && container2_layer1 == 0 && container2_layer2 == 0 && container2_layer3 == 0 && container2_layer9 > 0 && container3_layer9 == 0 )
        {
            result=true;
            sequence="*1-" + container1_layer1  + "#1T" + ",2-"+ container1_layer3  + "#1B" + ",3-" + container2_layer9  + "#2B@";
        }
        // check for 1 - FULL and 2 - Top - Middle - Bottom
        else if (container1_layer1 == 0 && container1_layer2 == 0 && container1_layer3 == 0 && container1_layer9 > 0 && container2_layer1 > 0 && container2_layer2 > 0 && container2_layer3 > 0 && container2_layer9 == 0 && container3_layer9 == 0 )
        {
            result=true;
            sequence="*1-" + container1_layer9  + "#1F" + ",2-"+ container2_layer1  + "#2T" + ",3-" + container2_layer2  + "#2M" + ",4-" + container2_layer3  + "#2B@";
        }
        // check for 2 - FULL and 1 - Top - Middle - Bottom
        else if (container1_layer1 > 0 && container1_layer2 > 0 && container1_layer3 > 0 && container1_layer9 == 0 && container2_layer1 == 0 && container2_layer2 == 0 && container2_layer3 == 0 && container2_layer9 > 0 && container3_layer9 == 0 )
        {
            result=true;
            sequence="*1-" + container1_layer1  + "#1F" + ",2-"+ container1_layer2  + "#1M" + ",3-" + container1_layer3  + "#1B" + ",4-" + container2_layer9  + "#2F@";
        }
        // check for 1 - Top - Bottom and 2 - Top - Bottom
        else if (container1_layer1 > 0 && container1_layer2 == 0 && container1_layer3 > 0 && container1_layer9 > 0 && container2_layer1 > 0 && container2_layer2 == 0 && container2_layer3 > 0 && container2_layer9 == 0 && container3_layer9 == 0 )
        {
            result=true;
            sequence="*1-" + container1_layer1  + "#1T" + ",2-"+ container1_layer3  + "#1B" + ",3-"+ container2_layer1  + "#2T" + ",4-" + container2_layer3  + "#2B@";
        }
        // check for 1 - Top - Middle - Bottom and 2 - Top - Middle - Bottom
        else if (container1_layer1 > 0 && container1_layer2 > 0 && container1_layer3 > 0 && container1_layer9 > 0 && container2_layer1 > 0 && container2_layer2 > 0 && container2_layer3 > 0 && container2_layer9 == 0 && container3_layer9 == 0 )
        {
            result=true;
            sequence="*1-"+ container1_layer1  + "#1T" + ",2-" + container1_layer2  + "#1M" + ",3-" + container1_layer3  + "#1B" + ",4-"+ container2_layer1  + "#2T" + ",5-" + container2_layer2  + "#2M" + ",6-" + container2_layer3  + "#2B@";
        }
        else
        {
            result=false;
            sequence="0";
        }

        res.close();
        db.close();
        return sequence;
    }


    public ArrayList<TodslipList> getCompletedtodsliplist()
    {
        ArrayList<TodslipList> array_list = new ArrayList<TodslipList>();
        Integer i=0;

        //hp = new HashMap();
        SQLiteDatabase db = this.getReadableDatabase();
        Cursor res =  db.rawQuery( "select seasoncode,todslipnumber,plotnumber,villagenameuni,farmernameuni,gatsurveno from todsliplist WHERE iscompleted=1 order by villagenameuni,farmernameuni,gatsurveno;", null );
        if (res.getCount() >0) {
            res.moveToFirst();
            while (res.isAfterLast() == false) {
                TodslipList plot1 = new TodslipList();
                plot1.setSeasoncode(res.getInt(res.getColumnIndex("seasoncode")));
                plot1.setPlotnumber(res.getInt(res.getColumnIndex("todslipnumber")));
                plot1.setPlotnumber(res.getInt(res.getColumnIndex("plotnumber")));
                plot1.setVillagenameuni(res.getString(res.getColumnIndex("villagenameuni")));
                plot1.setFarmernameuni(res.getString(res.getColumnIndex("farmernameuni")));
                plot1.setGatsurveno(res.getString(res.getColumnIndex("gatsurveno")));
                array_list.add(plot1);
                //array_list.add(res.getString(res.getColumnIndex(PLOTLIST_COLUMN_NAME)));
                res.moveToNext();
                i++;
            }
        }
        res.close();
        db.close();
        return array_list;
    }
        public Boolean isPlotcompleted(Integer seasonyear,Integer plotnumber)
        {
            Boolean ret=false;

            //hp = new HashMap();
            SQLiteDatabase db = this.getReadableDatabase();
            Cursor res =  db.rawQuery( "select area from plotlist WHERE area is not null and seasoncode="+Integer.toString(seasonyear)+ " and plotnumber="+Integer.toString(plotnumber)+ ";", null );
            if (res.getCount() >0)
            {
                res.moveToFirst();
                if (res.isAfterLast() == false)
                {
                    if (res.getColumnIndex("area")>=0)
                    {
                        ret=true;
                    }
                    else
                    {
                        ret=false;
                    }
                }
                else
                {
                    ret=false;
                }
            }
            res.close();
            db.close();
            return ret;
        }
    public Boolean isMinpointcompleted(Integer seasonyear,Integer plotnumber)
    {
        Boolean ret=false;

        //hp = new HashMap();
        SQLiteDatabase db = this.getReadableDatabase();
        Cursor res =  db.rawQuery( "select * from plotareadetail WHERE seasoncode="+Integer.toString(seasonyear)+ " and plotnumber="+Integer.toString(plotnumber)+ ";", null );
        if (res.getCount() >=4)
        {
            ret=true;
        }
        else
        {
            ret=false;
        }
        res.close();
        db.close();
        return ret;
    }

        public ArrayList<ListItem> getAllPlotlistareadetail(Integer seasonyear,Integer plotnumber) {
            ArrayList<ListItem> array_list = new ArrayList<ListItem>();
            Integer i=0;

            //hp = new HashMap();
            SQLiteDatabase db = this.getReadableDatabase();
            Cursor res =  db.rawQuery( "select serialnumber,latitude,longitude from plotareadetail WHERE seasoncode="+Integer.toString(seasonyear)+ " and plotnumber="+Integer.toString(plotnumber)+" order by serialnumber;", null );
            res.moveToFirst();
            ListItem point1;
            point1= new ListItem();
            ListItem lastpoint1 = new ListItem();
            while(res.isAfterLast() == false)
            {
                if (i>0)
                {
                    lastpoint1=point1;
                }
                point1 = new ListItem();
                point1.setSerialnumber(res.getInt(res.getColumnIndex("serialnumber")));
                point1.setLatitude(res.getDouble(res.getColumnIndex("latitude")));
                point1.setLongitude(res.getDouble(res.getColumnIndex("longitude")));
                float[] distance=new float[3];
                if (i>0)
                {
                    Location.distanceBetween(point1.getLatitude(),point1.getLongitude(),lastpoint1.getLatitude(),lastpoint1.getLongitude(),distance);
                }
                point1.setDistance(distance[0]);
                array_list.add(point1);
                //array_list.add(res.getString(res.getColumnIndex(PLOTLIST_COLUMN_NAME)));
                res.moveToNext();
                i++;
            }
            res.close();
            db.close();
            return array_list;
        }

        public ArrayList<MapPoint> getAllpoints(Integer seasonyear,Integer plotnumber) {
            ArrayList<MapPoint> array_list = new ArrayList<MapPoint>();

            //hp = new HashMap();
            SQLiteDatabase db = this.getReadableDatabase();
            Cursor res =  db.rawQuery( "select latitude,longitude from plotareadetail WHERE seasoncode="+Integer.toString(seasonyear)+ " and plotnumber="+Integer.toString(plotnumber)+" order by serialnumber;", null );
            res.moveToFirst();

            while(res.isAfterLast() == false)
            {
                MapPoint point1 = new MapPoint();
                point1.Latitude=(res.getDouble(res.getColumnIndex("latitude")));
                point1.Longitude=(res.getDouble(res.getColumnIndex("longitude")));
                array_list.add(point1);
                res.moveToNext();
            }
            res.close();
            db.close();
            return array_list;
        }

    public ArrayList<MobileCombo> getTyreGadiList(Integer seasoncode,Integer todslipnumber)
    {
        ArrayList<MobileCombo> array_list = new ArrayList<MobileCombo>();
        Integer i=0;

        //hp = new HashMap();
        //cast(tyregadinumber as text)||'-'||gadiwanname
        SQLiteDatabase db = this.getReadableDatabase();
        Cursor res =  db.rawQuery( "select tyregadicode,cast(tyregadinumber as text)||'-'||gadiwanname as gadiwanname from todslipvehiclelist WHERE vehiclecategorycode=3  and seasoncode="+Integer.toString(seasoncode)+ " and todslipnumber="+Integer.toString(todslipnumber)+ " order by tyregadinumber;", null );
        if (res.getCount() >0)
        {
            MobileCombo MobileCombo1 = new MobileCombo();
            MobileCombo1.setId(0);
            MobileCombo1.setName("निवडा टायरगाडी");
            array_list.add(MobileCombo1);
            res.moveToFirst();
            while (res.isAfterLast() == false)
            {
                MobileCombo1 = new MobileCombo();
                MobileCombo1.setId(res.getInt(res.getColumnIndex("tyregadicode")));
                MobileCombo1.setName(res.getString(res.getColumnIndex("gadiwanname")));
                array_list.add(MobileCombo1);
                //array_list.add(res.getString(res.getColumnIndex(PLOTLIST_COLUMN_NAME)));
                res.moveToNext();
                i++;
            }
        }
        else
        {
            Cursor res1 =  db.rawQuery( "select vehiclecode as tyregadicode, vehiclenumber as gadiwanname from todslipvehiclelist WHERE vehiclecategorycode<>3  and seasoncode="+Integer.toString(seasoncode)+ " and todslipnumber="+Integer.toString(todslipnumber)+ ";", null );
            if (res1.getCount() >0) {
                MobileCombo MobileCombo1 = new MobileCombo();
                MobileCombo1.setId(0);
                MobileCombo1.setName("निवडा वाहन");
                array_list.add(MobileCombo1);
                res1.moveToFirst();
                while (res1.isAfterLast() == false) {
                    MobileCombo1 = new MobileCombo();
                    MobileCombo1.setId(res1.getInt(res.getColumnIndex("tyregadicode")));
                    MobileCombo1.setName(res1.getString(res.getColumnIndex("gadiwanname")));
                    array_list.add(MobileCombo1);
                    //array_list.add(res.getString(res.getColumnIndex(PLOTLIST_COLUMN_NAME)));
                    res1.moveToNext();
                    i++;
                }
            }
            res1.close();
        }
        res.close();
        db.close();
        return array_list;
    }

    public MobileCombo getTyreGadi(Integer seasoncode,Integer todslipnumber,Integer tyregadicode)
    {
        Integer i=0;

        //hp = new HashMap();
        SQLiteDatabase db = this.getReadableDatabase();
        Cursor res =  db.rawQuery( "select tyregadicode,cast(tyregadinumber as text)||'-'||gadiwanname as gadiwanname from todslipvehiclelist WHERE vehiclecategorycode=3  and seasoncode="+Integer.toString(seasoncode)+ " and todslipnumber="+Integer.toString(todslipnumber)+ " and tyregadicode="+Integer.toString(tyregadicode)+" order by tyregadinumber;", null );
        MobileCombo MobileCombo1 = new MobileCombo();
        if (res.getCount() >0)
        {
            res.moveToFirst();
            if (res.isAfterLast() == false)
            {
                MobileCombo1.setId(res.getInt(res.getColumnIndex("tyregadicode")));
                MobileCombo1.setName(res.getString(res.getColumnIndex("gadiwanname")));
                //array_list.add(res.getString(res.getColumnIndex(PLOTLIST_COLUMN_NAME)));
            }
            /*else
            {
                MobileCombo1.setId(0);
                MobileCombo1.setName("निवडा टायरगाडी");
            }*/
        }
        else {
            Cursor res1 = db.rawQuery("select vehiclecode as tyregadicode, vehiclenumber as gadiwanname from todslipvehiclelist WHERE vehiclecategorycode<>3  and seasoncode=" + Integer.toString(seasoncode) + " and todslipnumber=" + Integer.toString(todslipnumber) + " and vehiclecode=" + Integer.toString(tyregadicode) + ";", null);
            if (res1.getCount() > 0) {
                res1.moveToFirst();
                if (res1.isAfterLast() == false) {
                    MobileCombo1.setId(res1.getInt(res.getColumnIndex("tyregadicode")));
                    MobileCombo1.setName(res1.getString(res.getColumnIndex("gadiwanname")));
                    //array_list.add(res.getString(res.getColumnIndex(PLOTLIST_COLUMN_NAME)));
                }
                /*else {
                    MobileCombo1.setId(0);
                    MobileCombo1.setName("निवडा वाहन");
                }*/
                res1.close();
            }
        }
        res.close();
        db.close();
        return MobileCombo1;
    }


    public ArrayList<MobileCombo> getContainerList()
    {
        ArrayList<MobileCombo> array_list = new ArrayList<MobileCombo>();
        Integer i=0;

        //hp = new HashMap();
        SQLiteDatabase db = this.getReadableDatabase();
        Cursor res =  db.rawQuery( "select containercode, containername from container;", null );
        if (res.getCount() >0)
        {
            MobileCombo MobileCombo1 = new MobileCombo();
            MobileCombo1.setId(0);
            MobileCombo1.setName("निवडा ट्रेलर");
            array_list.add(MobileCombo1);
            res.moveToFirst();
            while (res.isAfterLast() == false)
            {
                MobileCombo1 = new MobileCombo();
                MobileCombo1.setId(res.getInt(res.getColumnIndex("containercode")));
                MobileCombo1.setName(res.getString(res.getColumnIndex("containername")));
                array_list.add(MobileCombo1);
                //array_list.add(res.getString(res.getColumnIndex(PLOTLIST_COLUMN_NAME)));
                res.moveToNext();
                i++;
            }
        }
        res.close();
        db.close();
        return array_list;
    }

    public MobileCombo getContainer(Integer containercode)
    {
        Integer i=0;

        //hp = new HashMap();
        SQLiteDatabase db = this.getReadableDatabase();
        Cursor res =  db.rawQuery( "select containercode, containername from container where containercode="+containercode.toString()+";", null );
        MobileCombo MobileCombo1 = new MobileCombo();
        if (res.getCount() >0)
        {
            res.moveToFirst();
            if (res.isAfterLast() == false)
            {
                MobileCombo1.setId(res.getInt(res.getColumnIndex("containercode")));
                MobileCombo1.setName(res.getString(res.getColumnIndex("containername")));
            }
        }
        else
        {
            MobileCombo1.setId(0);
            MobileCombo1.setName("निवडा ट्रेलर");
        }
        res.close();
        db.close();
        return MobileCombo1;
    }

    public ArrayList<MobileCombo> getLayerList()
    {
        ArrayList<MobileCombo> array_list = new ArrayList<MobileCombo>();
        Integer i=0;

        //hp = new HashMap();
        SQLiteDatabase db = this.getReadableDatabase();
        Cursor res =  db.rawQuery( "select layercode, layername from layer;", null );
        if (res.getCount() >0)
        {
            MobileCombo MobileCombo1 = new MobileCombo();
            MobileCombo1.setId(0);
            MobileCombo1.setName("निवडा थर");
            array_list.add(MobileCombo1);
            res.moveToFirst();
            while (res.isAfterLast() == false)
            {
                MobileCombo1 = new MobileCombo();
                MobileCombo1.setId(res.getInt(res.getColumnIndex("layercode")));
                MobileCombo1.setName(res.getString(res.getColumnIndex("layername")));
                array_list.add(MobileCombo1);
                //array_list.add(res.getString(res.getColumnIndex(PLOTLIST_COLUMN_NAME)));
                res.moveToNext();
                i++;
            }
        }
        res.close();
        db.close();
        return array_list;
    }

    public MobileCombo getLayer(Integer layercode)
    {
        Integer i=0;

        //hp = new HashMap();
        SQLiteDatabase db = this.getReadableDatabase();
        Cursor res =  db.rawQuery( "select layercode, layername from layer where layercode="+layercode.toString()+";", null );
        MobileCombo MobileCombo1 = new MobileCombo();
        if (res.getCount() >0)
        {
            res.moveToFirst();
            if (res.isAfterLast() == false)
            {
                MobileCombo1.setId(res.getInt(res.getColumnIndex("layercode")));
                MobileCombo1.setName(res.getString(res.getColumnIndex("layername")));
            }
        }
        else
        {
            MobileCombo1.setId(0);
            MobileCombo1.setName("निवडा थर");
        }
        res.close();
        db.close();
        return MobileCombo1;
    }


        public Integer maxserialnumber(Integer seasonyear,Integer plotnumber) {
            Integer srno;

            SQLiteDatabase db = this.getReadableDatabase();

            //+ " and plotnumber=" +new String[] {String.valueOf(plotnumber)};
            //Cursor cursor = db.rawQuery("SELECT Max(serialnumber) FROM plotareadetail WHERE seasoncode=? ;", new String[] {String.valueOf(seasonyear)},null );
            Cursor cursor = db.rawQuery("SELECT Max(serialnumber) FROM plotareadetail WHERE seasoncode="+Integer.toString(seasonyear)+ " and plotnumber="+Integer.toString(plotnumber)+";",null );
            if (cursor != null) {
                cursor.moveToFirst();
                 srno = cursor.getInt(0);
            } else {
                srno=0;

            }
            cursor.close();
            db.close();
            return srno+1;
        }
        /*public Integer lastid(Integer seasonyear,Integer plotnumber) {
            Integer id;

            SQLiteDatabase db = this.getReadableDatabase();

            //+ " and plotnumber=" +new String[] {String.valueOf(plotnumber)};
            //Cursor cursor = db.rawQuery("SELECT Max(serialnumber) FROM plotareadetail WHERE seasoncode=? ;", new String[] {String.valueOf(seasonyear)},null );
            Cursor cursor = db.rawQuery("SELECT Max(id) FROM plotareadetail WHERE seasoncode="+Integer.toString(seasonyear)+ " and plotnumber="+Integer.toString(plotnumber)+";",null );
            if (cursor != null) {
                cursor.moveToFirst();
                id = cursor.getInt(0);
            } else {
                id=0;

            }
            return id;
        }*/
        public ArrayList<PlotList> downloadPlots(String result)
        {
            ArrayList<PlotList> plots = new ArrayList<PlotList>();
            try
            {
                JSONArray jArray = new JSONArray(result);
                for (int i = 0; i < jArray.length(); i++)
                {
                    JSONObject json_data = jArray.getJSONObject(i);
                    PlotList plot = new PlotList();
                    plot.setSeasoncode(json_data.getInt("seasoncode"));
                    plot.setPlotnumber(json_data.getInt("plotnumber"));
                    plot.setFarmername(json_data.getString("farmername"));
                    plot.setVillagename(json_data.getString("villagename"));
                    plot.setGatsurveno(json_data.getString("gatsurveno"));
                    plots.add(plot);
                }
            } catch (JSONException e)
            {
                Log.e("log_tag", "Error parsing data " + e.toString());
            }
            return plots;
        }

    public Integer getUploadPlotSeason()
    {
        Integer id;
        SQLiteDatabase db = this.getReadableDatabase();
        Cursor cursor = db.rawQuery("select h.seasoncode " +
                "from plotlist h " +
                "WHERE h.area is not null " +
                "order by seasoncode,plotnumber LIMIT 1;",null );
        if (cursor != null) {
            cursor.moveToFirst();
            id = cursor.getInt(0);
        } else {
            id=0;
        }
        return id;
    }

    public Integer getUploadPlotNumber()
    {
        Integer id;
        SQLiteDatabase db = this.getReadableDatabase();
        Cursor cursor = db.rawQuery("select h.plotnumber " +
                "from plotlist h " +
                "WHERE h.area is not null " +
                "order by seasoncode,plotnumber LIMIT 1;",null );
        if (cursor != null) {
            cursor.moveToFirst();
            id = cursor.getInt(0);
        } else {
            id=0;
        }
        return id;
    }

        public JSONArray getAllmeasuredplotdetail()
        {
            //hp = new HashMap();
            String sql =  "select * from (select d.seasoncode,d.plotnumber,d.serialnumber,d.latitude,d.longitude, '' as selfie, '' as idproof, '' as passbook " +
                    "from plotareadetail d,plotlist h " +
                    "WHERE d.seasoncode=h.seasoncode " +
                    "and d.plotnumber=h.plotnumber " +
                    "and h.area is not null " +
                    "union all " +
                    "select h.seasoncode,h.plotnumber,99 as serialnumber,0 as latitude,0 as longitude, h.selfie,h.aadhar as idproof, h.passbook " +
                    "from plotlist h " +
                    "where h.area is not null) " +
                    "where seasoncode=" + getUploadPlotSeason() + " and plotnumber=" + getUploadPlotNumber() + "\n" +
                    "order by seasoncode,plotnumber,serialnumber;";
            SQLiteDatabase db = this.getReadableDatabase();
            Cursor res =  db.rawQuery(sql, null );
            JSONArray retVal = new JSONArray();
            res.moveToFirst();
            retVal=cursorToJson(res);
            res.close();
            db.close();
            return retVal;
        }

    public JSONArray getmeasuredplotdetail(Integer seasonyear,Integer plotnumber)
    {
        //hp = new HashMap();
        String sql =  "select * from (select d.seasoncode,d.plotnumber,d.serialnumber,d.latitude,d.longitude, '' as selfie, '' as idproof, '' as passbook " +
                "from plotareadetail d,plotlist h " +
                "WHERE d.seasoncode=h.seasoncode " +
                "and d.plotnumber=h.plotnumber " +
                "and h.area is not null " +
                "union all " +
                "select h.seasoncode,h.plotnumber,99 as serialnumber,0 as latitude,0 as longitude, h.selfie,h.aadhar as idproof, h.passbook " +
                "from plotlist h " +
                "where h.area is not null) " +
                "where seasoncode=" + seasonyear + " and plotnumber=" + plotnumber + "\n" +
                "order by seasoncode,plotnumber,serialnumber;";
        SQLiteDatabase db = this.getReadableDatabase();
        Cursor res =  db.rawQuery(sql, null );
        JSONArray retVal = new JSONArray();
        res.moveToFirst();
        retVal=cursorToJson(res);
        res.close();
        db.close();
        return retVal;
    }

    public JSONArray getAllfieldslipbyvehiclecode(Integer vehiclecode)
    {
        //hp = new HashMap();
        String sql =  "select f.seasoncode,f.fieldslipnumber,f.fieldslipdate,f.plotnumber,f.farmercategorycode,f.farmercode,f.villagecode,f.subvillagecode\n" +
                ",vehiclecategorycode,vehiclecode,tyregadicode,hrsubcontractorcode,trsubcontractorcode,hrtrsubcontractorcode,caneconditioncode\n" +
                ",slipboycode,distance,layercode,trailornumber,containercode,viadistance,todslipnumber \n" +
                "from fieldslip f\n" +
                "where (f.vehiclecode="+vehiclecode+" or f.tyregadicode="+vehiclecode+")\n" +
                "order by fieldslipnumber;";
        SQLiteDatabase db = this.getReadableDatabase();
        Cursor res =  db.rawQuery(sql, null );
        JSONArray retVal = new JSONArray();
        res.moveToFirst();
        retVal=cursorToJson(res);
        res.close();
        db.close();
        return retVal;
    }

    public JSONArray getAllmeasuredplotimagedetail()
    {
        //hp = new HashMap();
        String sql =  "select h.seasoncode,h.plotnumber,h.selfie from plotlist h WHERE h.area is not null order by h.seasoncode,h.plotnumber;";
        SQLiteDatabase db = this.getReadableDatabase();
        Cursor res =  db.rawQuery(sql, null );
        JSONArray retVal = new JSONArray();
        res.moveToFirst();
        retVal=cursorToJson(res);
        res.close();
        db.close();
        return retVal;
    }

    public JSONArray Uploadtodslipcompleted(Integer seasoncode,Integer todslipnumber)
    {
        //hp = new HashMap();
        String sql =  "select h.seasoncode,h.todslipnumber from todsliplist h WHERE seasoncode="+seasoncode.toString()+" and todslipnumber="+todslipnumber.toString()+" order by h.seasoncode,h.todslipnumber;";
        SQLiteDatabase db = this.getReadableDatabase();
        Cursor res =  db.rawQuery(sql, null );
        JSONArray retVal = new JSONArray();
        res.moveToFirst();
        retVal=cursorToJson(res);
        res.close();
        db.close();
        return retVal;
    }
    public Integer deleteUploadedtodslips (Integer seasoncode,Integer todslipnumber)
    {
        SQLiteDatabase db = this.getWritableDatabase();
        //db.execSQL("PRAGMA foreign_keys=ON");
        return db.delete("todsliplist",
                "seasoncode = ? and todslipnumber = ?",
                new String[] { Integer.toString(seasoncode),Integer.toString(todslipnumber)});
    }
    /*public JSONObject getAllmeasuredplotimagedetail()
    {
        //hp = new HashMap();
        String sql =  "select h.seasoncode,h.plotnumber,h.selfie from plotlist h WHERE h.area is not null order by h.seasoncode,h.plotnumber;";
        SQLiteDatabase db = this.getReadableDatabase();
        Cursor res =  db.rawQuery(sql, null );
        JSONObject retVal = new JSONObject();
        res.moveToFirst();
        retVal=cursorToJsonobj(res);
        res.close();
        db.close();
        return retVal;
    }*/
        static JSONArray cursorToJson(Cursor c)
        {
            JSONArray retVal = new JSONArray();
            c.moveToFirst();
            while(c.isAfterLast() == false)
            {
                JSONObject obj = new JSONObject();
                for(int i=0; i<c.getColumnCount(); i++)
                {
                    String cName = c.getColumnName(i);
                    try {
                        switch (c.getType(i)) {
                            case Cursor.FIELD_TYPE_INTEGER:
                                obj.put(cName, c.getInt(i));
                                break;
                            case Cursor.FIELD_TYPE_FLOAT:
                                obj.put(cName, c.getFloat(i));
                                break;
                            case Cursor.FIELD_TYPE_STRING:
                                obj.put(cName, c.getString(i));
                                break;
                            case Cursor.FIELD_TYPE_BLOB:
                                obj.put(cName, bytesToHex(c.getBlob(i)));
                                break;
                        }
                    }
                    catch(Exception ex)
                    {
                        Log.e(TAG, "Exception converting cursor column to json field: " + cName);
                    }
                }
                retVal.put(obj);
                c.moveToNext();
                }
                return retVal;
            }

    static JSONObject cursorToJsonobj(Cursor c)
    {
        JSONObject retVal = new JSONObject();
        c.moveToFirst();
        while(c.isAfterLast() == false)
        {
            for(int i=0; i<c.getColumnCount(); i++) {
                String cName = c.getColumnName(i);
                try {
                    switch (c.getType(i)) {
                        case Cursor.FIELD_TYPE_INTEGER:
                            retVal.put(cName, c.getInt(i));
                            break;
                        case Cursor.FIELD_TYPE_FLOAT:
                            retVal.put(cName, c.getFloat(i));
                            break;
                        case Cursor.FIELD_TYPE_STRING:
                            retVal.put(cName, c.getString(i));
                            break;
                        case Cursor.FIELD_TYPE_BLOB:
                            retVal.put(cName, bytesToHex(c.getBlob(i)));
                            break;
                    }
                }
                catch(Exception ex) {
                    Log.e(TAG, "Exception converting cursor column to json field: " + cName);
                }
            }
            //retVal.put(obj);
            c.moveToNext();
        }
        return retVal;
    }
    private static final char[] HEX_ARRAY = "0123456789ABCDEF".toCharArray();
    public static String bytesToHex(byte[] bytes) {
        char[] hexChars = new char[bytes.length * 2];
        for (int j = 0; j < bytes.length; j++) {
            int v = bytes[j] & 0xFF;
            hexChars[j * 2] = HEX_ARRAY[v >>> 4];
            hexChars[j * 2 + 1] = HEX_ARRAY[v & 0x0F];
        }
        return new String(hexChars);
    }

}