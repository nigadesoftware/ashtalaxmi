package com.example.firstapp;

import androidx.appcompat.app.AppCompatActivity;

import android.content.Intent;
import android.os.Bundle;
import android.text.Spanned;
import android.util.Base64;
import android.util.Log;
import android.view.View;
import android.widget.AdapterView;
import android.widget.Button;
import android.widget.EditText;
import android.widget.ListView;
import android.widget.TextView;
import android.widget.Toast;

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

import java.util.ArrayList;
import java.util.HashMap;
import java.util.Map;

public class fieldslipMainActivity extends AppCompatActivity {

    private String URLline = "";
    private EditText etUname, etPass;
    private Button btn,btnup,btnlogout,btnimgup;
    private TextView HyperLink;
    Spanned Text;
    public static String publicFirstName, publicHobby;
    dbHelper mydb;
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_fieldslip_main);
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
            showVehiclefieldsliplist();
            //final LocationManager manager = (LocationManager) getSystemService(Context.LOCATION_SERVICE);

            /*if (!manager.isProviderEnabled(LocationManager.GPS_PROVIDER)) {
                buildAlertMessageNoGps();
            }*/
            btn = (Button) findViewById(R.id.btnDownload);
            btn.setOnClickListener(new View.OnClickListener() {
                @Override
                public void onClick(View v) {
                    btn.setEnabled(false);
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
                    Intent intent = new Intent(fieldslipMainActivity.this, home.class);
                    startActivity(intent);
                    finish();
                }
            });
        }
    }
    private void getVolley()
    {

        URLline = "http://206.84.230.83/webservice/controller/todslipforfieldslipRestController.php?view=download&slipboyuserid="+Long.toString(Global.uid);
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


    public void parseData(String response)
    {
        try {
            JSONArray dataArray = new JSONArray(response);
            mydb= new dbHelper(this);
            //if (jsonObject.getString("status").equals("true")) {
            //JSONArray dataArray = jsonObject.getJSONArray(response);
            for (int i = 0; i < dataArray.length(); i++)
            {
                JSONObject dataobj = dataArray.getJSONObject(i);
                //Boolean result = mydb.insertPLot(dataobj.getInt("SEASONCODE"),dataobj.getInt("PLOTNUMBER"),dataobj.getString("FARMERNAMEUNI"),dataobj.getString("VILLAGENAMEUNI"),dataobj.getString("GUTNUMBER"),dataobj.getString("VARIETYNAMEUNI"));
                //insertTodslip                                 (Integer seasoncode,                Integer todslipnumber,                  String todslipdate,               Integer plotnumber,               Integer farmercategorycode                  ,Integer farmercode              ,Integer villagecode               ,Integer hrsubcontractorcode                ,Integer trsubcontractorcode                          ,Integer hrtrsubcontractorcode                     ,Integer caneconditioncode                  ,Integer slipboycode               ,String farmernameuni                   ,String villagenameuni                  ,String transporternameuni                            ,String harvesternameuni                             ,String harvestertransporternameuni                           ,String gatsurveno)
                int b = dataobj.getInt("SEASONCODE");
                int a = dataobj.optInt("HRTRSUBCONTRACTORCODE",0);
                Boolean result = mydb.insertTodslip(dataobj.getInt("SEASONCODE"),dataobj.getInt("TODSLIPNUMBER"),dataobj.getString("TODSLIPDATE"),dataobj.getInt("PLOTNUMBER"),dataobj.getInt("FARMERCATEGORYCODE"),dataobj.getInt("FARMERCODE"),dataobj.getInt("VILLAGECODE"),dataobj.optInt("HRSUBCONTRACTORCODE",0),dataobj.optInt("TRSUBCONTRACTORCODE",0),dataobj.optInt("HRTRSUBCONTRACTORCODE",0),dataobj.getInt("CANECONDITIONCODE"),dataobj.getInt("SLIPBOYCODE"),dataobj.getString("FARMERNAMEUNI"),dataobj.getString("VILLAGENAMEUNI"),dataobj.optString("TRANSPORTERNAMEUNI",""),dataobj.optString("HARVESTERNAMEUNI",""),dataobj.optString("HARVESTERTRANSPORTERNAMEUNI",""),dataobj.getString("GATSURVENO"),dataobj.getInt("LASTSERIALNUMBER"));
                JSONArray vehicleArray = dataobj.getJSONArray("VEHICLES");
                for (int j = 0; j < vehicleArray.length(); j++)
                {
                    JSONObject vehicleobj = vehicleArray.getJSONObject(j);
                    Boolean result1 = mydb.insertTodslipvehiclelist(vehicleobj.getInt("SEASONCODE"),vehicleobj.getInt("TODSLIPNUMBER"),vehicleobj.getInt("VEHICLECATEGORYCODE"),vehicleobj.optInt("VEHICLECODE",0),vehicleobj.optInt("TYREGADICODE",0),vehicleobj.optString("VEHICLENUMBER",""),vehicleobj.optInt("TYREGADINUMBER",0),vehicleobj.optString("GADIWANNAMEUNI",""));
                }
                //publicFirstName = dataobj.getString("name");
                //publicHobby = dataobj.getString("hobby");
            }
            //showPendinglist();
            //showCompletedlist();
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
        //showPendinglist();
        //showCompletedlist();
    }

    private void showPendinglist()
    {
        Intent myIntent = getIntent();
        mydb = new dbHelper(this);
        ArrayList userList = mydb.getPendingtodsliplist();
        if (userList.size()>0) {
            final ListView lv = (ListView) findViewById(R.id.lsvTodsliplist);
            lv.setAdapter(new TodsliplistAdapter(this, userList));
            lv.setOnItemClickListener(new AdapterView.OnItemClickListener() {
                @Override
                public void onItemClick(AdapterView<?> a, View view, int position, long id) {
                    TodslipList todsliplist1 = (TodslipList) lv.getItemAtPosition(position);
                    //Toast.makeText(plot.this, "Selected :" + " " + user.getSerialnumber(), Toast.LENGTH_SHORT).show();
                    Intent intent = new Intent (view.getContext(), fieldslip.class);
                    intent.putExtra("seasonyear",todsliplist1.getSeasoncode().toString());
                    intent.putExtra("todslipnumber",todsliplist1.getTodslipnumber().toString());
                    intent.putExtra("plotnumber",todsliplist1.getPlotnumber().toString());
                    intent.putExtra("farmername",todsliplist1.getFarmernameuni().toString());
                    intent.putExtra("villagename",todsliplist1.getVillagenameuni().toString());
                    intent.putExtra("gatsurveno",todsliplist1.getGatsurveno().toString());
                    intent.putExtra("harvestername",todsliplist1.getHarvesternameuni().toString());
                    intent.putExtra("transportername",todsliplist1.getTransporternameuni().toString());
                    intent.putExtra("harvestertransportername",todsliplist1.getHarvestertransporternameuni().toString());
                    intent.putExtra("caneconditioncode",todsliplist1.getCaneconditioncode().toString());
                    intent.putExtra("lastserialnumber",todsliplist1.getLastserialnumber().toString());
                    startActivity(intent);
                }
            });
        }
    }
    private void showVehiclefieldsliplist()
    {
        Intent myIntent = getIntent();
        mydb = new dbHelper(this);
        ArrayList userList = mydb.getVehiclefieldsliplist();
        if (userList.size()>0)
        {
            final ListView lv = (ListView) findViewById(R.id.lsvTriplist);
            lv.setAdapter(new VehiclefieldsliplistAdapter(this, userList));
            lv.setOnItemClickListener(new AdapterView.OnItemClickListener() {
                @Override
                public void onItemClick(AdapterView<?> a, View view, int position, long id) {
                    Vehiclefieldsliplist vehicle1 = (Vehiclefieldsliplist) lv.getItemAtPosition(position);
                    //Toast.makeText(plot.this, "Selected :" + " " + user.getSerialnumber(), Toast.LENGTH_SHORT).show();
                    Intent intent = new Intent (view.getContext(),fieldsliplist.class);
                    intent.putExtra("vehiclecode",vehicle1.getVehiclecode().toString());
                    intent.putExtra("vehiclecategorycode",vehicle1.getVehiclecategorycode().toString());
                    intent.putExtra("vehiclenumber",vehicle1.getVehiclenumber());
                    intent.putExtra("vehiclecategory",vehicle1.getVehiclecategory());
                    startActivity(intent);
                }
            });
        }
    }
}
