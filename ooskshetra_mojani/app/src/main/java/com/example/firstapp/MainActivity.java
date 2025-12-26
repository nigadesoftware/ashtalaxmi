package com.example.firstapp;

import androidx.appcompat.app.AlertDialog;
import androidx.appcompat.app.AppCompatActivity;

import android.content.Context;
import android.location.LocationManager;
import android.os.Bundle;
import android.text.Spanned;
import android.util.Base64;
import android.util.Log;
import android.view.View;
import android.content.Intent;
import android.widget.AdapterView;
import android.widget.Button;
import android.widget.EditText;
import android.widget.ListView;
import android.widget.TextView;
import android.widget.Toast;
import android.content.DialogInterface;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.Map;

import com.android.volley.AuthFailureError;
import com.android.volley.NetworkResponse;
import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.StringRequest;
import com.android.volley.toolbox.Volley;
import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

public class MainActivity extends AppCompatActivity
{
    private String URLline = "";
    private EditText etUname, etPass;
    private Button btn,btnup,btnlogout,btnimgup;
    private TextView HyperLink;
    Spanned Text;
    public static String publicFirstName, publicHobby;
    dbHelper mydb;
    @Override
    protected void onCreate(Bundle savedInstanceState)
    {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);
        /*HyperLink = (TextView)findViewById(R.id.txvWebsite);
        Text = Html.fromHtml("<a href='http://206.84.230.83/'>Plot Selection for Measurement</a>");
        HyperLink.setMovementMethod(LinkMovementMethod.getInstance());
        HyperLink.setText(Text);*/
        mydb = new dbHelper(this);
        mydb.setUid();
        if (Global.uid == 0)
        {
            Intent intent = new Intent (getApplicationContext(),userlogin.class);
            startActivity(intent);
            finish();
        }
        else
            {
            showPendinglist();
            showCompletedlist();
            //final LocationManager manager = (LocationManager) getSystemService(Context.LOCATION_SERVICE);

            /*if (!manager.isProviderEnabled(LocationManager.GPS_PROVIDER)) {
                buildAlertMessageNoGps();
            }*/
            btn = (Button) findViewById(R.id.btnDownload);
            btn.setOnClickListener(new View.OnClickListener() {
                @Override
                public void onClick(View v) {
                    btn.setEnabled(false);
                /*new Handler().postDelayed(new Runnable() {
                    @Override
                    public void run() {
                        getVolley();
                    }
                }, 1000); // Millisecond 1000 = 1 sec*/
                    getVolley();
                }
            });
            /*btnup = (Button) findViewById(R.id.btnUpload);
            btnup.setOnClickListener(new View.OnClickListener() {
                @Override
                public void onClick(View v) {
                    btnup.setEnabled(false);
                    setVolley();
                }
            });*/
            btnlogout = (Button) findViewById(R.id.btnAgriculture);
            btnlogout.setOnClickListener(new View.OnClickListener() {
                @Override
                public void onClick(View v) {
                    //mydb.deleteLoginuser();
                    //mydb.setUid();
                    Intent intent = new Intent(MainActivity.this, home.class);
                    startActivity(intent);
                    finish();
                }
            });
        }
    }
    private void getVolley()
    {

        URLline ="http://206.84.230.83/webservice/controller/plotformeasurementRestController.php?view=download&measurementuserid="+Long.toString(Global.uid);
        Log.d("getxxx",URLline);

        StringRequest stringRequest = new StringRequest(Request.Method.GET, URLline,
                new Response.Listener<String>() {
                    @Override
                    public void onResponse(String response) {

                        Log.d("strrrrr",">>"+response);
                        parseData(response);
                        showPendinglist();
                        Toast.makeText(getApplicationContext(), "डाऊनलोड झाले आहे", Toast.LENGTH_LONG).show();
                        btn.setEnabled(true);
                    }
                },
                new Response.ErrorListener() {
                    @Override
                    public void onErrorResponse(VolleyError error) {
                        NetworkResponse response = error.networkResponse;
                        if(response != null && response.data != null) {
                            //displaying the error in toast if occurrs
                            Toast.makeText(getApplicationContext(), "डाऊनलोड झाले नाही, इंटरनेटची उपलब्धता तपासा" + error.getMessage(), Toast.LENGTH_SHORT).show();
                            btn.setEnabled(true);
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
    /*private void setVolley()
    {
        // request queue
        RequestQueue requestQueue = Volley.newRequestQueue(this);


        URLline = Global.staticip + "/webservice/controller/plotmeasuredRestController.php?view=upload&measurementuserid=1";
        Log.d("getxxx",URLline);
        JSONArray postparams = new JSONArray();
        mydb= new dbHelper(this);
        postparams=mydb.getAllmeasuredplotdetail();
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
                            mydb.deleteUploadedplots();
                            showCompletedlist();
                            btnup.setEnabled(true);
                            Toast.makeText(getApplicationContext(), "अपलोड झाले आहे", Toast.LENGTH_LONG).show();
                    }
                },
                new Response.ErrorListener() {
                    *//*@Override
                    public void onErrorResponse(VolleyError error) {
                        //displaying the error in toast if occurrs +error.getMessage()
                        Toast.makeText(getApplicationContext(), "अपलोड झाले नाही, इंटरनेटची उपलब्धता तपासा", Toast.LENGTH_LONG).show();
                        btnup.setEnabled(true);
                    }*//*
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
        *//*req.setRetryPolicy(new DefaultRetryPolicy(
                (int) TimeUnit.SECONDS.toMillis(6000), //After the set time elapses the request will timeout
                0,
                DefaultRetryPolicy.DEFAULT_BACKOFF_MULT));*//*
        req.setRetryPolicy(new DefaultRetryPolicy(20000,
                DefaultRetryPolicy.DEFAULT_MAX_RETRIES,
                DefaultRetryPolicy.DEFAULT_BACKOFF_MULT));
        requestQueue.add(req);
        requestQueue.start();
    }*/


    public void parseData(String response)
    {
        try {
            JSONArray dataArray = new JSONArray(response);
            mydb= new dbHelper(this);
            //if (jsonObject.getString("status").equals("true")) {
                //JSONArray dataArray = jsonObject.getJSONArray(response);
                for (int i = 0; i < dataArray.length(); i++) {

                    JSONObject dataobj = dataArray.getJSONObject(i);
                    Boolean result = mydb.insertPLot(dataobj.getInt("SEASONCODE"),dataobj.getInt("PLOTNUMBER"),dataobj.getString("FARMERNAMEUNI"),dataobj.getString("VILLAGENAMEUNI"),dataobj.getString("GUTNUMBER"),dataobj.getString("VARIETYNAMEUNI"),dataobj.getInt("INAREAOUTAREA"));
                    //publicFirstName = dataobj.getString("name");
                    //publicHobby = dataobj.getString("hobby");
                }
                showPendinglist();
                showCompletedlist();
                //Intent intent = new Intent(MainActivity.this,second_activity.class);
                //startActivity(intent);
            //}
        } catch (JSONException e) {
            e.printStackTrace();
        }

    }

    @Override
    protected void onResume() {
        super.onResume();
        showPendinglist();
        showCompletedlist();
    }

    private void showPendinglist()
    {
        Intent myIntent = getIntent();
        mydb = new dbHelper(this);
        ArrayList userList = mydb.getPendingplotlist();
        if (userList.size()>0) {
            final ListView lv = (ListView) findViewById(R.id.lsvPlotlist);
            lv.setAdapter(new PlotListAdapter(this, userList));
            lv.setOnItemClickListener(new AdapterView.OnItemClickListener() {
                @Override
                public void onItemClick(AdapterView<?> a, View view, int position, long id) {
                    PlotList user = (PlotList) lv.getItemAtPosition(position);
                    //Toast.makeText(plot.this, "Selected :" + " " + user.getSerialnumber(), Toast.LENGTH_SHORT).show();
                    Intent intent = new Intent (view.getContext(),plot.class);
                    intent.putExtra("seasonyear",user.getSeasoncode().toString());
                    intent.putExtra("plotnumber",user.getPlotnumber().toString());
                    intent.putExtra("farmername",user.getFarmername().toString());
                    intent.putExtra("villagename",user.getVillagename().toString());
                    intent.putExtra("gatsurveno",user.getGatsurveno().toString());
                    intent.putExtra("inareaoutarea",user.getInareaoutarea().toString());
                    intent.putExtra("variety",user.getVariety().toString());
                    startActivity(intent);
                }
            });
        }
    }
    private void showCompletedlist()
    {
        Intent myIntent = getIntent();
        mydb = new dbHelper(this);
        ArrayList userList = mydb.getCompletedplotlist();
        if (userList.size()>0)
        {
            final ListView lv = (ListView) findViewById(R.id.lsvPlotListCompleted);
            lv.setAdapter(new PlotListAdapter(this, userList));
            lv.setOnItemClickListener(new AdapterView.OnItemClickListener() {
                @Override
                public void onItemClick(AdapterView<?> a, View view, int position, long id) {
                    PlotList user = (PlotList) lv.getItemAtPosition(position);
                    //Toast.makeText(plot.this, "Selected :" + " " + user.getSerialnumber(), Toast.LENGTH_SHORT).show();
                    Intent intent = new Intent (view.getContext(),plot.class);
                    intent.putExtra("seasonyear",user.getSeasoncode().toString());
                    intent.putExtra("plotnumber",user.getPlotnumber().toString());
                    intent.putExtra("farmername",user.getFarmername().toString());
                    intent.putExtra("villagename",user.getVillagename().toString());
                    intent.putExtra("gatsurveno",user.getGatsurveno().toString());
                    intent.putExtra("inareaoutarea",user.getInareaoutarea().toString());
                    intent.putExtra("variety",user.getVariety().toString());
                    startActivity(intent);
                }
            });
        }
        else
        {
            final ListView lv = (ListView) findViewById(R.id.lsvPlotListCompleted);
            lv.setAdapter(null);
        }
    }




}
