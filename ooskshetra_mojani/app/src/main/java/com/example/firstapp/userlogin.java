package com.example.firstapp;

import androidx.appcompat.app.AppCompatActivity;

import android.app.ActionBar;
import android.content.Context;
import android.content.Intent;
import android.graphics.Color;
import android.graphics.drawable.ColorDrawable;
import android.os.Build;
import android.os.Bundle;
import android.provider.Settings;
import android.text.util.Linkify;
import android.util.Base64;
import android.util.Log;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
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

import java.util.HashMap;
import java.util.Map;

import android.telephony.TelephonyManager;

public class userlogin extends AppCompatActivity
{
    dbHelper mydb;
    private String URLline = "";
    private Button btn;
    private String usr,pw;
    private String dId;
    private Integer serno;
    @Override
    protected void onCreate(Bundle savedInstanceState)
    {
        try
        {
            super.onCreate(savedInstanceState);
            setContentView(R.layout.activity_userlogin);
            final TextView myClickableUrl = (TextView) findViewById(R.id.txvFactorywebsite);
            myClickableUrl.setText("Visit our Website");
            Linkify.addLinks(myClickableUrl, Linkify.WEB_URLS);

            final TextView myClickableUrl1 = (TextView) findViewById(R.id.txvWebsitelink1);
            myClickableUrl1.setText("www.nigadesoftware.com");
            Linkify.addLinks(myClickableUrl1, Linkify.WEB_URLS);
            String dvid;
            //dvid=getUniqueID();
            dvid="0";
            TextView devid = (TextView) findViewById(R.id.textView30);
            devid.setText("  "+dvid.toString());
        }
        catch (Exception e)
        {
            System.out.println("Thrown exception: " + e.getMessage());
        }
        mydb = new dbHelper(this);
        mydb.setUid();
        mydb.setStaticip();
        if (Global.uid == 0)
        {
            btn = (Button)findViewById(R.id.btnMeasurement);
            btn.setOnClickListener(new View.OnClickListener()
            {
                @Override
                public void onClick(View v)
                {
                     EditText userid = (EditText) findViewById(R.id.txtUserid);
                     EditText pwd = (EditText) findViewById(R.id.txtPassword);
                     EditText ser = (EditText) findViewById(R.id.txtServer);
                     usr = userid.getText().toString();
                     pw = pwd.getText().toString();
                     serno = Integer.parseInt(ser.getText().toString());
                     mydb.updateServer(serno);
                     mydb.setStaticip();
                     usr = encrypt(userid.getText().toString());
                     pw = encrypt(pwd.getText().toString());

                     try
                     {
                          //dId=getUniqueID();
                         dId="0";
                          getVolley(dId);
                     }
                     catch (Exception e)
                     {
                         System.out.println("Thrown exception: " + e.getMessage());
                     }
                     /*Intent intent = new Intent (v.getContext(),MainActivity.class);
                     startActivity(intent);
                     finish();*/
                }
            });
        }
        else
        {
            Intent intent = new Intent (getApplicationContext(),home.class);
            startActivity(intent);
            finish();
        }
    }

    public String getUniqueID(){
        String myAndroidDeviceId = "";
        TelephonyManager mTelephony = (TelephonyManager) getSystemService(Context.TELEPHONY_SERVICE);
        if (mTelephony.getDeviceId() != null){
            myAndroidDeviceId = mTelephony.getDeviceId();
        }else{
            myAndroidDeviceId = Settings.Secure.getString(getApplicationContext().getContentResolver(), Settings.Secure.ANDROID_ID);
        }
        return myAndroidDeviceId;
    }

    private void getVolley(String DeviceId)
    {
        URLline = Global.staticip + "/webservice/controller/verifyuserRestController.php?view=verifyuser&measurementuserid="+usr+"&measurementuserpwd="+pw+"&devid="+DeviceId;
        Log.d("getxxx",URLline);

        StringRequest stringRequest = new StringRequest(Request.Method.GET, URLline,
                new Response.Listener<String>() {
                    @Override
                    public void onResponse(String response) {

                        Log.d("strrrrr",">>"+response);
                        if (response.equals("0")) {
                            Toast.makeText(getApplicationContext(), "वापरकर्ता / पासवर्ड चुकीचा आहे" , Toast.LENGTH_LONG).show();
                            btn.setEnabled(true);
                        }
                        else if (response.equals("99")) {
                            Toast.makeText(getApplicationContext(), "मोबाईल रजिस्टर केलेला नाही." , Toast.LENGTH_LONG).show();
                            btn.setEnabled(true);
                        }
                        else
                        {
                            //parseData(response);
                            response=String.valueOf(Long.parseLong(response.toString())/2).toString();
                            mydb.insertLoginuser(Long.parseLong(response.toString()));
                            mydb.setUid();
                            if (Global.uid != 0) {
                                Intent intent = new Intent(getApplicationContext(),home.class);
                                startActivity(intent);
                                finish();
                            }
                        }

                    }
                },
                new Response.ErrorListener() {
                    @Override
                    public void onErrorResponse(VolleyError error) {
                        NetworkResponse response = error.networkResponse;
                        if(response != null && response.data != null) {
                            //displaying the error in toast if occurrs
                            Toast.makeText(getApplicationContext(), " इंटरनेटची उपलब्धता तपासा" + error.getMessage(), Toast.LENGTH_SHORT).show();
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
    public String encrypt(String txt)
    {
        int no;
        String ret="";
        for (int i=0;i<txt.length();i++)
        {
            no=(int)txt.charAt(i)+2304;
            ret=ret+String.format("%4s",Integer.toString(no)).replace(' ', '0');
        }
        return ret;
    }
    public String decrypt(String txt)
    {
        int no;
        String ret="";
        for (int i=0;i<txt.length();i=i+4)
        {
            no=Integer.parseInt(txt.substring(i,4))-2304;
            ret=ret+Character.toString((char)no);
        }
        return ret;
    }
}
