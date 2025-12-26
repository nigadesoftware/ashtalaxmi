package com.example.firstapp;

import androidx.appcompat.app.AppCompatActivity;

import android.content.Intent;
import android.os.Bundle;
import android.util.Base64;
import android.util.Log;
import android.view.View;
import android.widget.AdapterView;
import android.widget.Button;
import android.widget.ListView;
import android.widget.TextView;
import android.widget.Toast;

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
import com.android.volley.toolbox.Volley;

import org.json.JSONArray;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.Map;

public class fieldsliplist extends AppCompatActivity {

    private Button btnup,btncon,btnver;
    private TextView txv;
    dbHelper mydb;
    Integer isuploaded=0;
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_fieldsliplist);
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
            showFieldsliplist();
            btnup = (Button) findViewById(R.id.btnUpload);
            btnup.setEnabled(false);
            btnup.setOnClickListener(new View.OnClickListener() {
                @Override
                public void onClick(View v) {
                    btnup.setEnabled(false);
                    Intent myIntent = getIntent();
                    Integer vehiclecode = Integer.parseInt(myIntent.getStringExtra("vehiclecode"));
                    setVolley(vehiclecode,Global.staticip);
                    btnup.setEnabled(false);
                    btncon.setEnabled(true);
                }
            });
            btncon = (Button) findViewById(R.id.btnConfirmUploadTrip);
            btncon.setEnabled(false);
            btncon.setOnClickListener(new View.OnClickListener() {
                @Override
                public void onClick(View v) {
                    btncon.setEnabled(false);
                    Intent myIntent = getIntent();
                    Integer vehiclecode = Integer.parseInt(myIntent.getStringExtra("vehiclecode"));
                    setVolley(vehiclecode,Global.staticip);
                    btncon.setEnabled(false);
                }
            });
            btnver = (Button) findViewById(R.id.btnValidation);
            btnver.setEnabled(true);
            btnver.setOnClickListener(new View.OnClickListener() {
                @Override
                public void onClick(View v) {
                    String sequence;
                    Intent myIntent = getIntent();
                    Integer vehiclecode = Integer.parseInt(myIntent.getStringExtra("vehiclecode"));
                    btnver.setEnabled(false);
                    sequence = mydb.getFieldsliplistverified(vehiclecode);
                    if (sequence.equals("0"))
                    {
                        Toast.makeText(getApplicationContext(), "थर / साखळी चुकली आहे वा फिल्डस्लिप तयार केली नाही", Toast.LENGTH_LONG).show();
                        btnver.setEnabled(true);
                    }
                    else if (sequence.equals("-1"))
                    {
                        Toast.makeText(getApplicationContext(), "थर / साखळी डबल आहे वा चुकली आहे", Toast.LENGTH_LONG).show();
                        btnver.setEnabled(true);
                    }
                    else if (sequence.equals("-2"))
                    {
                        Toast.makeText(getApplicationContext(), "या वाहन प्रकारासाठी थर / साखळी ची निवड चुकीची आहे", Toast.LENGTH_LONG).show();
                        btnver.setEnabled(true);
                    }
                    else
                    {
                        Toast.makeText(getApplicationContext(), "वाहनाचे ट्रीपमघील सर्व फिल्डस्लिपची माहिती बरोबर आहे", Toast.LENGTH_LONG).show();
                        txv = (TextView) findViewById(R.id.txv_Sequence);
                        txv.setText(sequence);
                        btnup.setEnabled(true);
                        btnver.setEnabled(false);
                    }
                }
            });
        }
    }
    private void gotomain()
    {
        Intent intent = new Intent (this,fieldslipMainActivity.class);
        startActivity(intent);
    }
    private void showFieldsliplist()
    {
        Intent myIntent = getIntent();
        Button btnVerify,btnUpload,btnConfirmUploadTrip;
        mydb = new dbHelper(this);
        Integer vehiclecode = Integer.parseInt(myIntent.getStringExtra("vehiclecode"));
        String vehiclenumber = myIntent.getStringExtra("vehiclenumber");
        String vehiclecategory = myIntent.getStringExtra("vehiclecategory");
        TextView t_vehicle = (TextView) findViewById(R.id.txvVehicle);
        t_vehicle.setText("वाहन:"+vehiclecategory.toString()+" नं.:"+vehiclenumber.toString());
        ArrayList userList = mydb.getFieldsliplist(vehiclecode);
        if (userList.size()>0)
        {
            final ListView lv = (ListView) findViewById(R.id.lsvFieldsliplist);
            lv.setAdapter(new FieldsliplistbyvehicleAdapter(this, userList));
            lv.setOnItemClickListener(new AdapterView.OnItemClickListener() {
                @Override
                public void onItemClick(AdapterView<?> a, View view, int position, long id) {
                    Fieldsliplistbyvehiclecode fieldslip1 = (Fieldsliplistbyvehiclecode) lv.getItemAtPosition(position);
                    //Toast.makeText(plot.this, "Selected :" + " " + user.getSerialnumber(), Toast.LENGTH_SHORT).show();
                    Intent intent = new Intent (view.getContext(),fieldslip.class);
                    intent.putExtra("seasonyear",fieldslip1.getseasoncode().toString());
                    intent.putExtra("fieldslipnumber",fieldslip1.getfieldslipnumber().toString());
                    startActivity(intent);
                }
            });
            btnVerify = (Button)findViewById(R.id.btnValidation);
            btnUpload = (Button)findViewById(R.id.btnUpload);
            btnConfirmUploadTrip = (Button)findViewById(R.id.btnConfirmUploadTrip);
            btnVerify.setEnabled(true);
            btnUpload.setEnabled(false);
            btnConfirmUploadTrip.setEnabled(false);
        }
    }
    private void setVolley(Integer vehiclecode,String staticip)
    {
        // request queue
        RequestQueue requestQueue = Volley.newRequestQueue(this);
        String URLline;
        final Integer vhcode;
        vhcode=vehiclecode;
        URLline = "http://206.84.230.83:80/webservice/controller/fieldslipRestController.php?view=upload&slipboyuserid="+Long.toString(Global.uid);
        Log.d("getxxx",URLline);
        JSONArray postparams = new JSONArray();
        mydb= new dbHelper(this);
        postparams=mydb.getAllfieldslipbyvehiclecode(vehiclecode);
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
                        //mydb.deleteUploadedplots();
                        //btnup.setEnabled(true);
                        mydb.deleteUploadedfieldslips(vhcode);
                        Toast.makeText(getApplicationContext(), "अपलोड झाले आहे", Toast.LENGTH_LONG).show();
                        gotomain();
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
                            //btnup.setEnabled(true);
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
        /*req.setRetryPolicy(new DefaultRetryPolicy(
                (int) TimeUnit.SECONDS.toMillis(6000), //After the set time elapses the request will timeout
                0,
                DefaultRetryPolicy.DEFAULT_BACKOFF_MULT));*/
        req.setRetryPolicy(new DefaultRetryPolicy(20000,
                1,
                DefaultRetryPolicy.DEFAULT_BACKOFF_MULT));
        requestQueue.add(req);
        requestQueue.start();
    }
}