package com.example.firstapp;

import androidx.appcompat.app.AppCompatActivity;

import android.content.Intent;
import android.os.Bundle;
import android.util.Base64;
import android.util.Log;
import android.view.View;
import android.widget.ArrayAdapter;
import android.widget.Button;
import android.widget.Spinner;
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

public class fieldslip extends AppCompatActivity {

    dbHelper mydb;
    private Button btnfldslp,btnupdfldslp,btnhome,btnPlotcompleted;
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_fieldslip);
        btnfldslp = (Button) findViewById(R.id.btnAddfieldslip);
        btnfldslp.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                btnfldslp.setEnabled(false);
                addFieldSlip();
                btnfldslp.setEnabled(true);
            }
        });
        btnupdfldslp = (Button) findViewById(R.id.btnUpdatefieldslip);
        btnupdfldslp.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                btnupdfldslp.setEnabled(false);
                updateFieldSlip();
                btnupdfldslp.setEnabled(true);
            }
        });
        btnhome = (Button) findViewById(R.id.btnHome);
        btnhome.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                btnhome.setEnabled(false);
                Intent intent = new Intent(fieldslip.this, fieldslipMainActivity.class);
                startActivity(intent);
                finish();
            }
        });

        btnPlotcompleted = (Button) findViewById(R.id.btnPlotcompleted);
        btnPlotcompleted.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                btnPlotcompleted.setEnabled(false);
                Intent myIntent = getIntent();
                Integer seasonyear = Integer.parseInt(myIntent.getStringExtra("seasonyear"));
                Integer todslipnumber = Integer.parseInt(myIntent.getStringExtra("todslipnumber"));
                setVolley(seasonyear,todslipnumber);
            }
        });
        showlist();
    }
    private void addFieldSlip()
    {
        TextView t_seasoncode;
        TextView t_todslipnumber;
        TextView t_serialnumber;
        Spinner s_spnTyregadi;
        Spinner s_spnContainer;
        Spinner s_spnLayer;

        Integer seasoncode;
        Integer todslipnumber;
        Integer fieldslipnumber;
        Integer serialnumber;
        Integer tyregadicode;
        Integer containercode;
        Integer layercode;
        Integer vehiclecategorycode;

        long result;
        mydb = new dbHelper(this);
        t_seasoncode = (TextView) findViewById(R.id.txvSeasonyear);
        t_todslipnumber = (TextView) findViewById(R.id.txvTodslipnumber);
        t_serialnumber = (TextView) findViewById(R.id.etnSlipserialnumber);
        s_spnTyregadi = (Spinner) findViewById(R.id.spnTyregadi);
        s_spnContainer = (Spinner) findViewById(R.id.spnContainer);
        s_spnLayer = (Spinner) findViewById(R.id.spnLayer);

        seasoncode = Integer.parseInt(t_seasoncode.getText().toString());
        todslipnumber = Integer.parseInt(t_todslipnumber.getText().toString());
        serialnumber = Integer.parseInt(t_serialnumber.getText().toString());
        MobileCombo mobilecombo1 = (MobileCombo) s_spnTyregadi.getSelectedItem();
        tyregadicode=mobilecombo1.getId();
        MobileCombo mobilecombo2 = (MobileCombo) s_spnContainer.getSelectedItem();
        containercode=mobilecombo2.getId();
        MobileCombo mobilecombo3 = (MobileCombo) s_spnLayer.getSelectedItem();
        layercode=mobilecombo3.getId();

        fieldslipnumber = (todslipnumber+10000)*1000+(serialnumber);
        if (tyregadicode ==0)
        {
            Toast.makeText(getApplicationContext(), "गाडी नंबर निवडला नाही", Toast.LENGTH_SHORT).show();
            return;
        }
        if (containercode ==0)
        {
            Toast.makeText(getApplicationContext(), "ट्रेलर निवडला नाही", Toast.LENGTH_SHORT).show();
            return;
        }
        if (layercode ==0)
        {
            Toast.makeText(getApplicationContext(), "थर निवडला नाही", Toast.LENGTH_SHORT).show();
            return;
        }

        vehiclecategorycode=mydb.getVehiclecategorybytodslip(seasoncode,todslipnumber);

        if ((vehiclecategorycode ==1 || vehiclecategorycode ==3) && containercode>=2)
        {
            Toast.makeText(getApplicationContext(), "ट्रेलर चुकीचा निवडला आहे", Toast.LENGTH_SHORT).show();
            return;
        }

        //public boolean insertFieldsliplist (Integer seasoncode, Integer todslipnumber,Integer fieldslipnumber,Integer tyregadicode,Integer containercode,Integer layercode)
        result = mydb.insertFieldsliplist(seasoncode,todslipnumber,fieldslipnumber,tyregadicode,containercode,layercode);
        if (result == 1) {
            Toast.makeText(getApplicationContext(), "फिल्डस्लिप तयार झाली आहे", Toast.LENGTH_SHORT).show();
            Intent intent = new Intent (this,fieldslipMainActivity.class);
            startActivity(intent);
        } else {
            Toast.makeText(getApplicationContext(), "फिल्डस्लिप तयार झाली नाही", Toast.LENGTH_SHORT).show();
        }
    }
    private void updateFieldSlip()
    {
        TextView t_seasoncode;
        TextView t_todslipnumber;
        TextView t_serialnumber;
        Spinner s_spnTyregadi;
        Spinner s_spnContainer;
        Spinner s_spnLayer;

        Integer seasoncode;
        Integer todslipnumber;
        Integer fieldslipnumber;
        Integer serialnumber;
        Integer tyregadicode;
        Integer containercode;
        Integer layercode;
        Integer vehiclecategorycode;

        long result;
        mydb = new dbHelper(this);
        t_seasoncode = (TextView) findViewById(R.id.txvSeasonyear);
        t_todslipnumber = (TextView) findViewById(R.id.txvTodslipnumber);
        t_serialnumber = (TextView) findViewById(R.id.etnSlipserialnumber);
        s_spnTyregadi = (Spinner) findViewById(R.id.spnTyregadi);
        s_spnContainer = (Spinner) findViewById(R.id.spnContainer);
        s_spnLayer = (Spinner) findViewById(R.id.spnLayer);

        seasoncode = Integer.parseInt(t_seasoncode.getText().toString());
        todslipnumber = Integer.parseInt(t_todslipnumber.getText().toString());
        serialnumber = Integer.parseInt(t_serialnumber.getText().toString());
        MobileCombo mobilecombo1 = (MobileCombo) s_spnTyregadi.getSelectedItem();
        tyregadicode=mobilecombo1.getId();
        MobileCombo mobilecombo2 = (MobileCombo) s_spnContainer.getSelectedItem();
        containercode=mobilecombo2.getId();
        MobileCombo mobilecombo3 = (MobileCombo) s_spnLayer.getSelectedItem();
        layercode=mobilecombo3.getId();

        fieldslipnumber = (todslipnumber+10000)*1000+(serialnumber);

        if (tyregadicode ==0)
        {
            Toast.makeText(getApplicationContext(), "गाडी नंबर निवडला नाही", Toast.LENGTH_SHORT).show();
            return;
        }
        if (containercode ==0)
        {
            Toast.makeText(getApplicationContext(), "ट्रेलर निवडला नाही", Toast.LENGTH_SHORT).show();
            return;
        }
        if (layercode ==0)
        {
            Toast.makeText(getApplicationContext(), "थर निवडला नाही", Toast.LENGTH_SHORT).show();
            return;
        }

        vehiclecategorycode=mydb.getVehiclecategorybytodslip(seasoncode,todslipnumber);

        if ((vehiclecategorycode ==1 || vehiclecategorycode ==3) && containercode>=2)
        {
            Toast.makeText(getApplicationContext(), "ट्रेलर चुकीचा निवडला आहे", Toast.LENGTH_SHORT).show();
            return;
        }


        //public boolean insertFieldsliplist (Integer seasoncode, Integer todslipnumber,Integer fieldslipnumber,Integer tyregadicode,Integer containercode,Integer layercode)
        result = mydb.updateFieldsliplist(seasoncode,todslipnumber,fieldslipnumber,tyregadicode,containercode,layercode);
        if (result == 1) {
            Toast.makeText(getApplicationContext(), "फिल्डस्लिप बदल झाली आहे", Toast.LENGTH_SHORT).show();
            Intent intent = new Intent (this,fieldslipMainActivity.class);
            startActivity(intent);
        } else {
            Toast.makeText(getApplicationContext(), "फिल्डस्लिप बदल झाली नाही", Toast.LENGTH_SHORT).show();
        }
    }
    private void showfieldslip(Integer seasoncode, Integer fieldslipnumber)
    {
        mydb = new dbHelper(this);
        ArrayList<TodslipList> result;
        TodslipList fieldslip1;
        result = mydb.getFieldslip(seasoncode,fieldslipnumber);
        for (int counter = 0; counter < 1; counter++)
        {
            fieldslip1=result.get(counter);
            String seasonyear = fieldslip1.getSeasoncode().toString();
            String todslipnumber = fieldslip1.getTodslipnumber().toString();
            String plotnumber = fieldslip1.getPlotnumber().toString();
            String farmername = fieldslip1.getFarmernameuni();
            String villagename = fieldslip1.getVillagenameuni();
            String gatsurveno = fieldslip1.getGatsurveno();
            String harvestername = fieldslip1.getHarvesternameuni();
            String transportername = fieldslip1.getTransporternameuni();
            String harvestertransportername = fieldslip1.getHarvestertransporternameuni();
            String caneconditioncode = fieldslip1.getCaneconditioncode().toString();
            String lastserialnumber = fieldslip1.getLastserialnumber().toString();
            String vehiclecode = fieldslip1.getVehiclecode().toString();
            String containercode = fieldslip1.getContainercode().toString();
            String layercode = fieldslip1.getLayercode().toString();

            TextView t_seasonyear = (TextView) findViewById(R.id.txvSeasonyear);
            TextView t_todslipnumber = (TextView) findViewById(R.id.txvTodslipnumber);
            TextView t_plotnumber = (TextView) findViewById(R.id.txvPlotnumber);
            TextView t_farmername = (TextView) findViewById(R.id.txvFarmer);
            TextView t_villagename = (TextView) findViewById(R.id.txvVillage);
            TextView t_gatsurveno = (TextView) findViewById(R.id.txvGutsurvey);
            TextView t_harvester = (TextView) findViewById(R.id.txvHarvester);
            TextView t_transporter = (TextView) findViewById(R.id.txvTransporter);
            TextView t_canecondition = (TextView) findViewById(R.id.txvCanecondition);
            TextView t_lastserialnumber = (TextView) findViewById(R.id.etnSlipserialnumber);
            /*Spinner t_TyreGadicode = (Spinner) findViewById(R.id.spnTyregadi);
            Spinner t_containercode = (Spinner) findViewById(R.id.spnContainer);
            Spinner t_layercode = (Spinner) findViewById(R.id.spnLayer);*/


            t_seasonyear.setText(seasonyear);
            t_todslipnumber.setText(todslipnumber);
            t_plotnumber.setText(plotnumber);
            t_farmername.setText(farmername);
            t_villagename.setText(villagename);
            t_gatsurveno.setText(gatsurveno);
            t_harvester.setText(harvestername);
            t_transporter.setText(transportername);
            t_lastserialnumber.setText(Integer.toString(fieldslipnumber%1000));
            t_lastserialnumber.setEnabled(false);
            if (harvestertransportername.isEmpty() == false) {
                t_harvester.setText(harvestertransportername);
                t_transporter.setText(harvestertransportername);
            }
            if (caneconditioncode.equals("1")) {
                t_canecondition.setText("चांगला");
            }
            if (caneconditioncode.equals("2")) {
                t_canecondition.setText("जळका");
            }
            if (caneconditioncode.equals("3")) {
                t_canecondition.setText("वाळका");
            }

            mydb = new dbHelper(this);

            ArrayList<MobileCombo> tyregadi1 = new ArrayList<>();
            tyregadi1 = mydb.getTyreGadiList(Integer.parseInt(seasonyear), Integer.parseInt(todslipnumber));
            //fill data in spinner
            ArrayAdapter<MobileCombo> adapter3 = new ArrayAdapter<MobileCombo>(this, android.R.layout.simple_spinner_dropdown_item, tyregadi1);
            Spinner t_TyreGadi = (Spinner) findViewById(R.id.spnTyregadi);
            t_TyreGadi.setAdapter(adapter3);

            ArrayList<MobileCombo> container1 = new ArrayList<>();
            container1 = mydb.getContainerList();
            //fill data in spinner
            ArrayAdapter<MobileCombo> adapter1 = new ArrayAdapter<MobileCombo>(this, android.R.layout.simple_spinner_dropdown_item, container1);
            Spinner t_Container = (Spinner) findViewById(R.id.spnContainer);
            t_Container.setAdapter(adapter1);

            ArrayList<MobileCombo> layer1 = new ArrayList<>();
            layer1 = mydb.getLayerList();
            //fill data in spinner
            ArrayAdapter<MobileCombo> adapter2 = new ArrayAdapter<MobileCombo>(this, android.R.layout.simple_spinner_dropdown_item, layer1);
            Spinner t_Layer = (Spinner) findViewById(R.id.spnLayer);


            t_Container.setAdapter(adapter1);
            MobileCombo myItem1 = new MobileCombo();
            myItem1=mydb.getContainer(Integer.parseInt(containercode));
            t_Container.setSelection(adapter1.getPosition(myItem1));


            t_Layer.setAdapter(adapter2);
            MobileCombo myItem2 = new MobileCombo();
            myItem2=mydb.getLayer(Integer.parseInt(layercode));
            t_Layer.setSelection(adapter2.getPosition(myItem2));

            t_TyreGadi.setAdapter(adapter3);
            MobileCombo myItem3 = new MobileCombo();
            myItem3=mydb.getTyreGadi(Integer.parseInt(seasonyear), Integer.parseInt(todslipnumber),Integer.parseInt(vehiclecode));
            t_TyreGadi.setSelection(adapter3.getPosition(myItem3));
            Button btnupdfldslp,btnaddfldslp;
            btnupdfldslp = (Button) findViewById(R.id.btnUpdatefieldslip);
            btnupdfldslp.setEnabled(true);
            btnaddfldslp = (Button) findViewById(R.id.btnAddfieldslip);
            btnaddfldslp.setEnabled(false);

        }
    }
    private void showlist()
    {
        Intent myIntent = getIntent();
        String seasonyear = myIntent.getStringExtra("seasonyear");
        String todslipnumber = myIntent.getStringExtra("todslipnumber");
        if (myIntent.getStringExtra("fieldslipnumber")!= null && !myIntent.getStringExtra("fieldslipnumber").isEmpty())
        {
            String fieldslipnumber = myIntent.getStringExtra("fieldslipnumber");
            showfieldslip(Integer.parseInt(seasonyear),Integer.parseInt(fieldslipnumber));
        }
        else {
            String plotnumber = myIntent.getStringExtra("plotnumber");
            String farmername = myIntent.getStringExtra("farmername");
            String villagename = myIntent.getStringExtra("villagename");
            String gatsurveno = myIntent.getStringExtra("gatsurveno");
            String harvestername = myIntent.getStringExtra("harvestername");
            String transportername = myIntent.getStringExtra("transportername");
            String harvestertransportername = myIntent.getStringExtra("harvestertransportername");
            String caneconditioncode = myIntent.getStringExtra("caneconditioncode");
            String lastserialnumber = myIntent.getStringExtra("lastserialnumber");
            TextView t_seasonyear = (TextView) findViewById(R.id.txvSeasonyear);
            TextView t_todslipnumber = (TextView) findViewById(R.id.txvTodslipnumber);
            TextView t_plotnumber = (TextView) findViewById(R.id.txvPlotnumber);
            TextView t_farmername = (TextView) findViewById(R.id.txvFarmer);
            TextView t_villagename = (TextView) findViewById(R.id.txvVillage);
            TextView t_gatsurveno = (TextView) findViewById(R.id.txvGutsurvey);
            TextView t_harvester = (TextView) findViewById(R.id.txvHarvester);
            TextView t_transporter = (TextView) findViewById(R.id.txvTransporter);
            TextView t_canecondition = (TextView) findViewById(R.id.txvCanecondition);
            TextView t_lastserialnumber = (TextView) findViewById(R.id.etnSlipserialnumber);

            t_seasonyear.setText(seasonyear);
            t_todslipnumber.setText(todslipnumber);
            t_plotnumber.setText(plotnumber);
            t_farmername.setText(farmername);
            t_villagename.setText(villagename);
            t_gatsurveno.setText(gatsurveno);
            t_harvester.setText(harvestername);
            t_transporter.setText(transportername);
            Integer srno;
            if (lastserialnumber != null && !lastserialnumber.isEmpty()) {
                srno = Integer.parseInt(lastserialnumber.toString()) + 1;
                t_lastserialnumber.setText(srno.toString());
                t_lastserialnumber.setFocusable(false);
            } else {
                t_lastserialnumber.setText("1");
                t_lastserialnumber.setFocusable(false);
            }

            if (harvestertransportername!=null && !harvestertransportername.isEmpty() && !harvestertransportername.equals("null")) {
                t_harvester.setText(harvestertransportername);
                t_transporter.setText(harvestertransportername);
            }
            if (caneconditioncode.equals("1")) {
                t_canecondition.setText("चांगला");
            }
            if (caneconditioncode.equals("2")) {
                t_canecondition.setText("जळका");
            }
            if (caneconditioncode.equals("3")) {
                t_canecondition.setText("वाळका");
            }
            mydb = new dbHelper(this);
            Integer sea;
            Integer ts;
            ArrayList<MobileCombo> tyregadi1 = new ArrayList<>();
            sea = Integer.parseInt(t_seasonyear.getText().toString());
            ts = Integer.parseInt(t_todslipnumber.getText().toString());
            tyregadi1 = mydb.getTyreGadiList(sea, ts);
            //fill data in spinner
            ArrayAdapter<MobileCombo> adapter = new ArrayAdapter<MobileCombo>(this, android.R.layout.simple_spinner_dropdown_item, tyregadi1);
            Spinner t_TyreGadi = (Spinner) findViewById(R.id.spnTyregadi);
            t_TyreGadi.setAdapter(adapter);

            ArrayList<MobileCombo> container1 = new ArrayList<>();
            container1 = mydb.getContainerList();
            //fill data in spinner
            ArrayAdapter<MobileCombo> adapter1 = new ArrayAdapter<MobileCombo>(this, android.R.layout.simple_spinner_dropdown_item, container1);
            Spinner t_Container = (Spinner) findViewById(R.id.spnContainer);
            t_Container.setAdapter(adapter1);

            ArrayList<MobileCombo> layer1 = new ArrayList<>();
            layer1 = mydb.getLayerList();
            //fill data in spinner
            ArrayAdapter<MobileCombo> adapter2 = new ArrayAdapter<MobileCombo>(this, android.R.layout.simple_spinner_dropdown_item, layer1);
            Spinner t_Layer = (Spinner) findViewById(R.id.spnLayer);
            t_Layer.setAdapter(adapter2);
            //spinner_country.setSelection(adapter.getPosition(myItem));//Optional to set the selected item.
            Button btnupdfldslp,btnaddfldslp;
            btnupdfldslp = (Button) findViewById(R.id.btnUpdatefieldslip);
            btnupdfldslp.setEnabled(false);
            btnaddfldslp = (Button) findViewById(R.id.btnAddfieldslip);
            btnaddfldslp.setEnabled(true);
        }
    }
    private void gotomain()
    {
        Intent intent = new Intent (this,fieldslipMainActivity.class);
        startActivity(intent);
    }
    private void setVolley(Integer seasoncode, Integer todslipnumber)
    {
        // request queue
        RequestQueue requestQueue = Volley.newRequestQueue(this);
        String URLline;
        final Integer sccode;
        final Integer tsnumber;
        sccode=seasoncode;
        tsnumber=todslipnumber;
        URLline = "http://206.84.230.83/webservice/controller/todslipRestController.php?view=upload&slipboyuserid="+Long.toString(Global.uid);
        Log.d("getxxx",URLline);
        JSONArray postparams = new JSONArray();
        mydb= new dbHelper(this);
        postparams=mydb.Uploadtodslipcompleted(seasoncode,todslipnumber);
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
                        mydb.deleteUploadedtodslips(sccode,tsnumber);
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
                DefaultRetryPolicy.DEFAULT_MAX_RETRIES,
                DefaultRetryPolicy.DEFAULT_BACKOFF_MULT));
        requestQueue.add(req);
        requestQueue.start();
    }
}
