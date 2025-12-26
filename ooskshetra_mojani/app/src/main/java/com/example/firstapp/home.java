package com.example.firstapp;

import android.content.Intent;
import android.net.Uri;
import android.os.Bundle;

import androidx.appcompat.app.AppCompatActivity;

import android.view.View;
import android.widget.Button;
import android.widget.Toast;

public class home extends AppCompatActivity {
    private Button btnMeasure,btnFieldslip,btnAgriculture,btnLogout,btnAgriculture2;
    dbHelper mydb;
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_home);
        mydb = new dbHelper(this);
        btnMeasure = (Button)findViewById(R.id.btnMeasurement);
        btnMeasure.setOnClickListener(new View.OnClickListener()
        {
            @Override
            public void onClick(View v)
            {
                 Intent intent = new Intent(v.getContext(),MainActivity.class);
                 startActivity(intent);
                 //finish();
            }
        });
        btnAgriculture = (Button) findViewById(R.id.btnAgriculture);
        btnAgriculture.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                Long pwd = mydb.setInnerPassword();
                String url = Global.staticip + "/mis/login.php?userid="+Long.toString(Global.uid)+"&paramid="+Long.toString(pwd)+"&yearcode=1";
                Intent i = new Intent(Intent.ACTION_VIEW);
                i.setData(Uri.parse(url));
                startActivity(i);
                //finish();
            }
        });
        btnAgriculture2 = (Button) findViewById(R.id.btnAgriculture2);
        btnAgriculture2.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                Long pwd = mydb.setInnerPassword();
                String url = Global.staticip + "/mis/login.php?userid="+Long.toString(Global.uid)+"&paramid="+Long.toString(pwd)+"&yearcode=2";
                Intent i = new Intent(Intent.ACTION_VIEW);
                i.setData(Uri.parse(url));
                startActivity(i);
                //finish();
            }
        });
        btnFieldslip = (Button) findViewById(R.id.btnFieldslip);
        btnFieldslip.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                Intent intent = new Intent(v.getContext(),fieldslipMainActivity.class);
                startActivity(intent);
                //finish();
            }
        });
        btnLogout = (Button) findViewById(R.id.btnLogout);
        btnLogout.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                mydb.deleteLoginuser();
                mydb.setUid();
                Intent intent = new Intent(home.this, userlogin.class);
                startActivity(intent);
                Toast.makeText(getApplicationContext(), "लाॅगआऊट केले आहे", Toast.LENGTH_SHORT).show();
                finish();
            }
        });
    }

}
