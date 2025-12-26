package com.example.firstapp;

import androidx.appcompat.app.AlertDialog;
import androidx.appcompat.app.AppCompatActivity;

import android.content.DialogInterface;
import android.content.Intent;
import android.os.Build;
import android.os.Bundle;
import android.app.Activity;
import android.content.Context;
import android.location.Criteria;
import android.location.Location;
import android.location.LocationListener;
import android.location.LocationManager;
import android.os.Bundle;
import android.provider.Settings;
import android.text.TextUtils;
import android.util.Log;
import android.view.View;
import android.widget.TextView;
import android.widget.Toast;

import java.util.ArrayList;
public class area extends AppCompatActivity {

    /** Called when the activity is first created. */
    private String provider;
    dbHelper mydb;
    LocationManager locationManager;
    @Override
    public void onCreate(Bundle savedInstanceState)
    {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_area); /*
         * Use the LocationManager class to
         * obtain GPS locations
         */
        final LocationManager manager = (LocationManager) getSystemService(Context.LOCATION_SERVICE);
        if (!manager.isProviderEnabled(LocationManager.GPS_PROVIDER)) {
            buildAlertMessageNoGps();
        }

        Intent myIntent = getIntent();
        String seasonyear = myIntent.getStringExtra("seasonyear");
        String plotnumber = myIntent.getStringExtra("plotnumber");
        TextView season = (TextView) findViewById(R.id.txvSeasonyear);
        TextView plotno = (TextView) findViewById(R.id.txvPlotnumber);
        season.setText(seasonyear);
        plotno.setText(plotnumber);

        locationManager = (LocationManager) getSystemService(Context.LOCATION_SERVICE);
        LocationListener mlocListener = new MyLocationListener();
        Criteria criteria = new Criteria();
        criteria.setAccuracy(Criteria.ACCURACY_COARSE);
        //criteria.setAccuracy(Criteria.ACCURACY_FINE);
        provider = locationManager.getBestProvider(criteria, true);
        locationManager.requestLocationUpdates(LocationManager.GPS_PROVIDER, 0, 0, mlocListener);
    }
    public void gotoplot(View view)
    {
        //Intent intent = new Intent (this,plot.class);
        //Intent myIntent = getIntent();
        //startActivity(myIntent);
        TextView t_seasoncode;
        TextView t_plotnumber;
        TextView t_latitude;
        TextView t_longitude;

        Integer seasoncode;
        Integer plotnumber;
        Integer serialnumber;
        Double latitude;
        Double longitude;

        Boolean result;

        mydb = new dbHelper(this);
        t_seasoncode= (TextView)findViewById(R.id.txvSeasonyear);
        t_plotnumber= (TextView)findViewById(R.id.txvPlotnumber);
        t_latitude= (TextView) findViewById(R.id.txvLatitude);
        t_longitude= (TextView) findViewById(R.id.txvLongitude);

        seasoncode = Integer.parseInt(t_seasoncode.getText().toString());
        plotnumber = Integer.parseInt(t_plotnumber.getText().toString());
        latitude = Double.parseDouble(t_latitude.getText().toString());
        longitude = Double.parseDouble(t_longitude.getText().toString());
        if (latitude==0.0 && longitude==0.0)
        {
            Toast.makeText(getApplicationContext(), "जीपीएस/लोकेशन बंद आहे वा व्यवस्थित सुरू झाले नाही",
                    Toast.LENGTH_LONG).show();
            return;
        }
        serialnumber= mydb.maxserialnumber(seasoncode,plotnumber);

        result=mydb.insertPLotareadetail (seasoncode, plotnumber, serialnumber,latitude,longitude);
        if (result==true)
        {
            Toast.makeText(getApplicationContext(),"माहीती साठवली आहे",Toast.LENGTH_SHORT).show();
            finish();
        }
        else
        {
           Toast.makeText(getApplicationContext(),"माहीती साठवली नाही",Toast.LENGTH_SHORT).show();
        }

    }
    private void buildAlertMessageNoGps() {
        final androidx.appcompat.app.AlertDialog.Builder builder = new androidx.appcompat.app.AlertDialog.Builder(this);
        builder.setMessage("जीपीएस/लोकेशन सुरू करणे गरजेचे अाहे?")
                .setCancelable(false)
                .setPositiveButton("होय", new DialogInterface.OnClickListener() {
                    public void onClick(@SuppressWarnings("unused") final DialogInterface dialog, @SuppressWarnings("unused") final int id) {
                        startActivity(new Intent(android.provider.Settings.ACTION_LOCATION_SOURCE_SETTINGS));
                    }
                })
                .setNegativeButton("नाही", new DialogInterface.OnClickListener() {
                    public void onClick(final DialogInterface dialog, @SuppressWarnings("unused") final int id) {
                        dialog.cancel();
                    }
                });
        final AlertDialog alert = builder.create();
        alert.show();
    }
    /* Class My Location Listener */
    public class MyLocationListener implements LocationListener
    {
        @Override
        public void onLocationChanged(Location loc)
        {
            try {
                loc.getLatitude();
                loc.getLongitude();
                TextView txvlatitude1 = (TextView) findViewById(R.id.txvLatitude);
                TextView txvlongitude1 = (TextView) findViewById(R.id.txvLongitude);
                txvlatitude1.setText(Double.toString(loc.getLatitude()));
                txvlongitude1.setText(Double.toString(loc.getLongitude()));
                /*String Text = "My current location is: " + "Latitude = "
                        + loc.getLatitude() + "Longitude = " + loc.getLongitude();
                Toast.makeText(getApplicationContext(), Text, Toast.LENGTH_SHORT)
                        .show();*/
                Log.d("TAG", "Starting..");
            }
            catch(Exception e)
            {
                Toast.makeText(getApplicationContext(),e.getMessage(),Toast.LENGTH_SHORT);
            }
        }

        @Override
        public void onProviderDisabled(String provider) {
            Toast.makeText(getApplicationContext(), "जीपीएस/लोकेशन बंद केले",
                    Toast.LENGTH_SHORT).show();
        }

        @Override
        public void onProviderEnabled(String provider) {
            Toast.makeText(getApplicationContext(), "जीपीएस/लोकेशन सुरू केले",
                    Toast.LENGTH_SHORT).show();
        }

        @Override
        public void onStatusChanged(String provider, int status, Bundle extras) {
        }



    }/* End of Class MyLocationListener */
}/* End of UseGps Activity */
