package com.example.firstapp;

import androidx.appcompat.app.AppCompatActivity;

import android.content.Context;
import android.content.Intent;
import android.os.Build;
import android.os.Bundle;
import android.provider.Settings;
import android.text.TextUtils;
import android.util.Base64;
import android.util.Log;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.ArrayAdapter;
import android.widget.ListView;
import android.widget.TextView;
import android.widget.Toast;
import java.util.ArrayList;
import android.widget.AdapterView;

import com.android.volley.AuthFailureError;
import com.android.volley.DefaultRetryPolicy;
import com.android.volley.NetworkError;
import com.android.volley.NetworkResponse;
import com.android.volley.NoConnectionError;
import com.android.volley.ParseError;
import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.ServerError;
import com.android.volley.TimeoutError;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.JsonArrayRequest;
import com.android.volley.toolbox.StringRequest;
import com.android.volley.toolbox.Volley;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.lang.Math;
import java.util.HashMap;
import java.util.Map;
import java.util.concurrent.TimeUnit;

public class plot extends AppCompatActivity {
    dbHelper mydb;
    private ListView mListView;
    private ArrayAdapter aAdapter;
    private Button btnup,btnaddpoint,btndeletepoint,btnverify, btnconfirm,btndeleteplot;
    private String URLline = "";
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_plot);
        btnup = (Button) findViewById(R.id.btnUpload);
        btnup.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                //btnup.setEnabled(false);
                btnconfirm.setEnabled(true);
                btnup.setEnabled(false);
                setVolley();
            }
        });
        btnconfirm = (Button) findViewById(R.id.btnConfirm);
        btnconfirm.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                btnup.setEnabled(false);
                btnconfirm.setEnabled(false);
                getVolley();
            }
        });
        btndeleteplot = (Button) findViewById(R.id.btnDeleteplot);
        btndeleteplot.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                deleteplot(v);
            }
        });
        showlist();
        showarea();
    }
    @Override
    protected void onResume()
    {
        super.onResume();
        showlist();
    }

    private void showlist()
    {
        Intent myIntent = getIntent();
        String seasonyear = myIntent.getStringExtra("seasonyear");
        String plotnumber = myIntent.getStringExtra("plotnumber");
        String farmername = myIntent.getStringExtra("farmername");
        String villagename = myIntent.getStringExtra("villagename");
        String gatsurveno = myIntent.getStringExtra("gatsurveno");
        String inareaoutareacode = myIntent.getStringExtra("inareaoutarea");
        String variety = myIntent.getStringExtra("variety");
        String inareaoutarea;

        TextView t_seasonyear = (TextView) findViewById(R.id.txvSeasonyear);
        TextView t_plotnumber = (TextView) findViewById(R.id.txvPlotnumber);
        TextView t_farmername = (TextView) findViewById(R.id.txvFarmer);
        TextView t_villagename = (TextView) findViewById(R.id.txvVillage);
        TextView t_gatsurveno = (TextView) findViewById(R.id.txvGutsurvey);
        TextView t_inareaoutarea = (TextView) findViewById(R.id.txvInareaoutarea);
        TextView t_variety = (TextView) findViewById(R.id.txvVariety);

        t_seasonyear.setText(seasonyear);
        t_plotnumber.setText(plotnumber);
        t_farmername.setText(farmername);
        t_villagename.setText(villagename);
        t_gatsurveno.setText(gatsurveno);
        t_variety.setText(variety);
        if (inareaoutareacode.equals("1")) {
            inareaoutarea = "कार्यक्षेत्रातील";
        }
        else {
            inareaoutarea = "गेटकेन";
        }
        t_inareaoutarea.setText(inareaoutarea);
        Integer pn;
        Integer sea;
        mydb = new dbHelper(this);
        sea = Integer.parseInt(t_seasonyear.getText().toString());
        pn = Integer.parseInt(t_plotnumber.getText().toString());
        ArrayList pointList = mydb.getAllPlotlistareadetail(sea,pn);
        final ListView lv = (ListView) findViewById(R.id.lsvPoint);
        lv.setAdapter(new CustomListAdapter(this, pointList));
        lv.setOnItemClickListener(new AdapterView.OnItemClickListener() {
            @Override
            public void onItemClick(AdapterView<?> a, View v, int position, long id) {
                ListItem point = (ListItem) lv.getItemAtPosition(position);
                Toast.makeText(plot.this, "Selected :" + " " + point.getSerialnumber(), Toast.LENGTH_SHORT).show();
            }
        });
        showarea();
        if (mydb.isPlotcompleted(sea,pn)==true)
        {
            btnaddpoint = (Button)findViewById(R.id.btnAddpoint);
            btndeletepoint = (Button)findViewById(R.id.btnDeletepoint);
            btnverify = (Button)findViewById(R.id.btnVerify);
            btndeleteplot = (Button)findViewById(R.id.btnVerify);
            btnaddpoint.setVisibility(View.INVISIBLE);
            btndeletepoint.setVisibility(View.INVISIBLE);
            btnverify.setVisibility(View.INVISIBLE);
            btnup.setVisibility(View.VISIBLE);
            btnconfirm.setVisibility(View.VISIBLE);
            btnconfirm.setEnabled(false);
            btndeleteplot.setVisibility(View.VISIBLE);
        }
        else if (mydb.isMinpointcompleted(sea,pn)==true)
        {
            btnaddpoint = (Button)findViewById(R.id.btnAddpoint);
            btndeletepoint = (Button)findViewById(R.id.btnDeletepoint);
            btnverify = (Button)findViewById(R.id.btnVerify);
            btndeleteplot = (Button)findViewById(R.id.btnVerify);
            btnaddpoint.setVisibility(View.VISIBLE);
            btndeletepoint.setVisibility(View.VISIBLE);
            btnverify.setVisibility(View.VISIBLE);
            btnup.setVisibility(View.INVISIBLE);
            btnconfirm.setVisibility(View.INVISIBLE);
            btndeleteplot.setVisibility(View.VISIBLE);
        }
        else if (inareaoutareacode.equals("2"))
        {
            btnaddpoint = (Button)findViewById(R.id.btnAddpoint);
            btndeletepoint = (Button)findViewById(R.id.btnDeletepoint);
            btnverify = (Button)findViewById(R.id.btnVerify);
            btndeleteplot = (Button)findViewById(R.id.btnVerify);
            btnaddpoint.setVisibility(View.VISIBLE);
            btndeletepoint.setVisibility(View.VISIBLE);
            btnverify.setVisibility(View.VISIBLE);
            btnup.setVisibility(View.INVISIBLE);
            btnconfirm.setVisibility(View.INVISIBLE);
            btndeleteplot.setVisibility(View.VISIBLE);
        }
        else
        {
            btnaddpoint = (Button)findViewById(R.id.btnAddpoint);
            btndeletepoint = (Button)findViewById(R.id.btnDeletepoint);
            btnverify = (Button)findViewById(R.id.btnVerify);
            btndeleteplot = (Button)findViewById(R.id.btnVerify);
            btnaddpoint.setVisibility(View.VISIBLE);
            btndeletepoint.setVisibility(View.VISIBLE);
            btnverify.setVisibility(View.INVISIBLE);
            btnup.setVisibility(View.INVISIBLE);
            btnconfirm.setVisibility(View.INVISIBLE);
            btndeleteplot.setVisibility(View.VISIBLE);
        }
    }
    private Double showarea()
    {
        Intent myIntent = getIntent();
        String seasonyear = myIntent.getStringExtra("seasonyear");
        String plotnumber = myIntent.getStringExtra("plotnumber");
        TextView season = (TextView) findViewById(R.id.txvSeasonyear);
        TextView plotno = (TextView) findViewById(R.id.txvPlotnumber);
        TextView tarea = (TextView) findViewById(R.id.txvArea);
        season.setText(seasonyear);
        plotno.setText(plotnumber);
        Integer sea;
        Integer pn;
        mydb = new dbHelper(this);
        sea = Integer.parseInt(season.getText().toString());
        pn = Integer.parseInt(plotno.getText().toString());
        ArrayList coordinates = mydb.getAllpoints(sea,pn);
        Double area = CalculatePolygonArea(coordinates);
        //Toast.makeText(getApplicationContext(),area.toString(),Toast.LENGTH_SHORT).show();
        tarea.setText(area.toString());
        return area;
    }

    private void gotomain()
    {
        Intent intent = new Intent (this,MainActivity.class);
        startActivity(intent);
    }

    private double CalculatePolygonArea(ArrayList<MapPoint> coordinates)
    {
        double area = 0;
        int cnt = coordinates.size();
        if (cnt > 2)
        {
            for (int i = 0; i < cnt-1; i++)
            {
                MapPoint p1 = coordinates.get(i);
                MapPoint p2 = coordinates.get(i+1);
                area += ConvertToRadian(p2.Longitude - p1.Longitude) * (2 + Math.sin(ConvertToRadian(p1.Latitude)) + Math.sin(ConvertToRadian(p2.Latitude)));
            }
            MapPoint p1 = coordinates.get(cnt-1);
            MapPoint p2 = coordinates.get(0);
            area += ConvertToRadian(p2.Longitude - p1.Longitude) * (2 + Math.sin(ConvertToRadian(p1.Latitude)) + Math.sin(ConvertToRadian(p2.Latitude)));
            area = Math.abs(area * 6378137 * 6378137 / 2)/10000;
        }
        return Math.round(area*100.0)/100.0;
    }

    private static double ConvertToRadian(double input)
    {
        return input * Math.PI / 180;
    }

    public void gotoarea(View view)
    {
        Intent intent = new Intent (this,area.class);
        TextView seasonyear = (TextView) findViewById(R.id.txvSeasonyear);
        TextView plotnumber = (TextView) findViewById(R.id.txvPlotnumber);
        intent.putExtra("seasonyear",seasonyear.getText().toString());
        intent.putExtra("plotnumber",plotnumber.getText().toString());
        startActivity(intent);
    }
    public void gotoverify(View view)
    {
        Intent intent = new Intent (this,verifyplot.class);
        TextView seasonyear = (TextView) findViewById(R.id.txvSeasonyear);
        TextView plotnumber = (TextView) findViewById(R.id.txvPlotnumber);
        intent.putExtra("seasonyear",seasonyear.getText().toString());
        intent.putExtra("plotnumber",plotnumber.getText().toString());
        Double area = showarea();
        intent.putExtra("area",area.toString());
        startActivity(intent);
    }
    public void deleteplot(View view)
    {
        Integer result;
        Integer seasoncode;
        Integer plotnumber;
        Intent intent = new Intent (this,verifyplot.class);
        TextView t_seasoncode = (TextView) findViewById(R.id.txvSeasonyear);
        TextView t_plotnumber = (TextView) findViewById(R.id.txvPlotnumber);
        seasoncode = Integer.parseInt(t_seasoncode.getText().toString());
        plotnumber = Integer.parseInt(t_plotnumber.getText().toString());
        mydb = new dbHelper(this);
        result = mydb.deletePlot(seasoncode,plotnumber);

        if (result == 1) {
            Toast.makeText(getApplicationContext(), "प्लॉट माहीती खोडली आहे", Toast.LENGTH_SHORT).show();
            gotomain();
        } else {
            Toast.makeText(getApplicationContext(), "प्लॉट माहीती खोडली नाही", Toast.LENGTH_SHORT).show();
        }

    }
    public void deletelastpoint(View view)
    {

        TextView t_seasoncode;
        TextView t_plotnumber;

        Integer seasoncode;
        Integer plotnumber;
        Integer serialnumber;
        Integer result;
        mydb = new dbHelper(this);
        t_seasoncode = (TextView) findViewById(R.id.txvSeasonyear);
        t_plotnumber = (TextView) findViewById(R.id.txvPlotnumber);


        seasoncode = Integer.parseInt(t_seasoncode.getText().toString());
        plotnumber = Integer.parseInt(t_plotnumber.getText().toString());


        serialnumber = mydb.maxserialnumber(seasoncode, plotnumber)-1;

        result = mydb.deletePlotareadetail(seasoncode,plotnumber,serialnumber);

        if (result == 1) {
            Toast.makeText(getApplicationContext(), "माहीती खोडली आहे", Toast.LENGTH_SHORT).show();
            showlist();
        } else {
            Toast.makeText(getApplicationContext(), "माहीती खोडली नाही", Toast.LENGTH_SHORT).show();
        }
    }
    private void setVolley()
    {
        Intent myIntent = getIntent();
        // request queue
        RequestQueue requestQueue = Volley.newRequestQueue(this);


        //URLline = Global.staticip + "/webservice/controller/plotmeasuredRestController.php?view=upload&measurementuserid="+Long.toString(Global.uid);
        URLline = "http://206.84.230.83:80/webservice/controller/plotmeasuredRestController.php?view=upload&measurementuserid="+Long.toString(Global.uid);
        Log.d("getxxx",URLline);
        JSONArray postparams = new JSONArray();
        mydb= new dbHelper(this);
        postparams=mydb.getmeasuredplotdetail(Integer.parseInt(myIntent.getStringExtra("seasonyear").toString()),Integer.parseInt(myIntent.getStringExtra("plotnumber").toString()));
        //postparams.put("city", "london");
        //postparams.put("timestamp", "1500134255");

        JsonArrayRequest req = new JsonArrayRequest(Request.Method.POST,
                URLline, postparams,
                new Response.Listener<JSONArray>() {
                    @Override
                    public void onResponse(JSONArray response)
                    {
                        Log.d("strrrrr", ">>" + response.toString());
                        //parseData(response);
                        //mydb.deleteUploadedplots(mydb.getUploadPlotSeason(),mydb.getUploadPlotNumber());
                        //btnup.setEnabled(true);
                        Toast.makeText(getApplicationContext(), "Press Confirm", Toast.LENGTH_LONG).show();
                        //gotomain();
                        getVolley();
                        btnconfirm.setEnabled(true);
                    }
                },
                new Response.ErrorListener() {
                    /*@Override
                    public void onErrorResponse(VolleyError error) {
                        //displaying the error in toast if occurrs +error.getMessage()
                        Toast.makeText(getApplicationContext(), "अपलोड झाले नाही, इंटरनेटची उपलब्धता तपासा", Toast.LENGTH_LONG).show();
                        btnup.setEnabled(true);
                    }*/
                    @Override
                    public void onErrorResponse(VolleyError error)
                    {
                        NetworkResponse response = error.networkResponse;
                        if(response != null && response.data != null) {
                            if (error instanceof TimeoutError || error instanceof NoConnectionError) {
                                Toast.makeText(getApplicationContext(),
                                        "अपलोड झाले नाही, इंटरनेटची उपलब्धता तपासा",
                                        Toast.LENGTH_LONG).show();
                            } else if (error instanceof AuthFailureError) {
                                //TODO
                                Toast.makeText(getApplicationContext(),
                                        "अपलोड झाले नाही, पासवर्ड मध्ये अडचण आहे",
                                        Toast.LENGTH_LONG).show();
                            } else if (error instanceof ServerError) {
                                //TODO
                                Toast.makeText(getApplicationContext(),
                                        "अपलोड झाले नाही,सर्व्हर डाऊन आहे",
                                        Toast.LENGTH_LONG).show();
                            } else if (error instanceof NetworkError) {
                                //TODO
                                Toast.makeText(getApplicationContext(),
                                        "अपलोड झाले नाही, नेटवर्क मध्ये अडचण आहे",
                                        Toast.LENGTH_LONG).show();
                            } else if (error instanceof ParseError) {
                                //TODO
                                Toast.makeText(getApplicationContext(),
                                        "अपलोड झाले नाही, पुन्हा प्रयत्न करा!",
                                        Toast.LENGTH_LONG).show();
                            }
                            btnup.setEnabled(true);
                        }
                        else
                        {
                            getVolley();
                        }
                    }
                })
        {
            @Override
            public String getBodyContentType()
            {
                return "application/json; charset=utf-8";
            }
            @Override
            public Map<String, String> getHeaders() throws AuthFailureError
            {
                Map<String, String> params = new HashMap<String, String>();
                params.put("Accept", "application/json");
                params.put("Content-Type", "application/json");
                String credentials = "makai"+":"+"Makai@123";
                String auth = "Basic "
                        + Base64.encodeToString(credentials.getBytes(),
                        Base64.NO_WRAP);
                params.put("Authorization", auth);

                return params;
            }
        };
        req.setRetryPolicy(new DefaultRetryPolicy((int) TimeUnit.SECONDS.toMillis(20000),
                1,
                DefaultRetryPolicy.DEFAULT_BACKOFF_MULT));
        requestQueue.add(req);
        requestQueue.start();
    }

    public Boolean parseData(String response)
    {
        Boolean isuploaded=false;
        try {
            JSONArray dataArray = new JSONArray(response);
            mydb= new dbHelper(this);
            Integer selfiecount=0,pbcount=0,idcount=0,pointcount=0;
            byte[] selfie,passbook, idproof;

            //if (jsonObject.getString("status").equals("true")) {
            //JSONArray dataArray = jsonObject.getJSONArray(response);
            for (int i = 0; i < dataArray.length(); i++) {

                JSONObject dataobj = dataArray.getJSONObject(i);

                selfie=mydb.getPlotselfie(dataobj.getInt("SEASONCODE"),dataobj.getInt("PLOTNUMBER"));
                passbook=mydb.getPlotpassbook(dataobj.getInt("SEASONCODE"),dataobj.getInt("PLOTNUMBER"));
                idproof=mydb.getPlotidproof(dataobj.getInt("SEASONCODE"),dataobj.getInt("PLOTNUMBER"));
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
                pointcount = mydb.getPointCount(dataobj.getInt("SEASONCODE"),dataobj.getInt("PLOTNUMBER"));
                if (dataobj.getInt("POINTCOUNT") == pointcount &&  dataobj.getInt("SELFIECOUNT") == selfiecount && dataobj.getInt("IDCOUNT") == idcount && dataobj.getInt("PBCOUNT") == pbcount)
                {
                    mydb.deleteUploadedplots(dataobj.getInt("SEASONCODE"),dataobj.getInt("PLOTNUMBER"));
                    isuploaded=true;
                }
                else
                {
                    isuploaded=false;
                }
                //publicFirstName = dataobj.getString("name");
                //publicHobby = dataobj.getString("hobby");
            }

            //Intent intent = new Intent(MainActivity.this,second_activity.class);
            //startActivity(intent);
            //}
        } catch (JSONException e) {
            e.printStackTrace();
        }
        return isuploaded;
    }

    private void getVolley()
    {
        Intent myIntent = getIntent();
        mydb= new dbHelper(this);
        URLline = "http://206.84.230.83/webservice/controller/plotformeasurementRestController.php?view=uploadcount&seasoncode=" + myIntent.getStringExtra("seasonyear").toString() + "&plotnumber=" + myIntent.getStringExtra("plotnumber").toString() + "&measurementuserid="+Long.toString(Global.uid);
        Log.d("getxxx",URLline);

        StringRequest stringRequest = new StringRequest(Request.Method.GET, URLline,
                new Response.Listener<String>() {
                    @Override
                    public void onResponse(String response) {

                        Log.d("strrrrr",">>"+response);
                        if (parseData(response)) {
                            gotomain();
                            Toast.makeText(getApplicationContext(), "अपलोड झाले आहे", Toast.LENGTH_LONG).show();
                            //btn.setEnabled(true);
                        }
                        else
                        {
                            gotomain();
                            Toast.makeText(getApplicationContext(), "अपलोड झाले नाही", Toast.LENGTH_LONG).show();
                        }
                    }
                },
                new Response.ErrorListener() {
                    @Override
                    public void onErrorResponse(VolleyError error) {
                        NetworkResponse response = error.networkResponse;
                        if(response != null && response.data != null) {
                            //displaying the error in toast if occurrs
                            Toast.makeText(getApplicationContext(), "अपलोड झाले नाही, इंटरनेटची उपलब्धता तपासा" + error.getMessage(), Toast.LENGTH_SHORT).show();
                            //btn.setEnabled(true);
                            gotomain();
                        }
                    }
                })
        {
            @Override
            public Map<String, String> getHeaders() throws AuthFailureError
            {
                Map<String, String> params = new HashMap<String, String>();
                params.put("Accept", "application/json");
                params.put("Content-Type", "application/json");
                params.put("Cache-Control","no-cache");
                String credentials = "makai"+":"+"Makai@123";
                String auth = "Basic "
                        + Base64.encodeToString(credentials.getBytes(),
                        Base64.NO_WRAP);
                params.put("Authorization", auth);

                return params;
            }
        };

        // request queue
        RequestQueue requestQueue = Volley.newRequestQueue(this);

        requestQueue.add(stringRequest);
    }

}
