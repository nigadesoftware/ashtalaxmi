package com.example.firstapp;

import androidx.appcompat.app.AppCompatActivity;

import android.content.Intent;
import android.graphics.Bitmap;
import android.graphics.BitmapFactory;
import android.os.Bundle;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.TextView;
import android.widget.Toast;

public class verifyplot extends AppCompatActivity {
    public Integer seasonyear;
    public Integer plotnumber;
    public Double area;
    public String remark;
    Button btnSelfie,btnIdproof,btnPassbook;
    dbHelper mydb;
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_verifyplot);
        Intent myIntent = getIntent();
        seasonyear = Integer.parseInt(String.valueOf(myIntent.getStringExtra("seasonyear")));
        plotnumber = Integer.parseInt(String.valueOf(myIntent.getStringExtra("plotnumber")));
        area = Double.parseDouble(String.valueOf(myIntent.getStringExtra("area")));
        btnSelfie = (Button) findViewById(R.id.btnSelfie);
        btnSelfie.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                gotoselfie(1);
            }
        });
        btnIdproof = (Button) findViewById(R.id.btnIdproof);
        btnIdproof.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                gotoselfie(2);
            }
        });
        btnPassbook = (Button) findViewById(R.id.btnPassbook);
        btnPassbook.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                gotoselfie(3);
            }
        });
        mydb = new dbHelper(this);
        ImageView imvSelfie = (ImageView) findViewById(R.id.imvSelfie);
        byte[] image;
        image=mydb.getPlotselfie(seasonyear,plotnumber);
        if (image != null)
        {
            Bitmap img = BitmapFactory.decodeByteArray(image, 0, image.length);
            imvSelfie.setImageBitmap(img);
        }
        else
        {
            imvSelfie.setImageBitmap(null);
        }
        ImageView imvIdproof = (ImageView) findViewById(R.id.imvIdproof);
        byte[] image1;
        image1=mydb.getPlotidproof (seasonyear,plotnumber);
        if (image1 != null)
        {
            Bitmap img1 = BitmapFactory.decodeByteArray(image1, 0, image1.length);
            imvIdproof.setImageBitmap(img1);
        }
        else
        {
            imvIdproof.setImageBitmap(null);
        }
        ImageView imvPassbook = (ImageView) findViewById(R.id.imvPassbook);
        byte[] image2;
        image2=mydb.getPlotpassbook (seasonyear,plotnumber);
        if (image2 != null)
        {
            Bitmap img2 = BitmapFactory.decodeByteArray(image2, 0, image2.length);
            imvPassbook.setImageBitmap(img2);
        }
        else
        {
            imvPassbook.setImageBitmap(null);
        }
    }
    public void updateremark(View view)
    {
        ImageView imvSelfie = (ImageView) findViewById(R.id.imvSelfie);
        EditText tremark = (EditText) findViewById(R.id.txtRemark);
        byte[] image;
        image=mydb.getPlotselfie(seasonyear,plotnumber);
        if (image == null)
        {
            Toast.makeText(getApplicationContext(),"सेल्फी घेतला नाही",Toast.LENGTH_SHORT).show();
            return;
        }
        if (tremark.getText().toString().length()==0)
        {
            Toast.makeText(getApplicationContext(),"शेरा भरला नाही",Toast.LENGTH_SHORT).show();
            return;
        }
        mydb = new dbHelper(this);
        remark= tremark.getText().toString();
        Boolean result = mydb.updatePlotlist(seasonyear,plotnumber,area,remark);
        if (result==true)
        {
            Toast.makeText(getApplicationContext(),"Plot Completed Successfully",Toast.LENGTH_SHORT).show();
            Intent intent = new Intent (this,MainActivity.class);
            startActivity(intent);
        }
        mydb.close();
    }
    private void gotoselfie(int doctype)
    {
        Intent intent = new Intent(getApplicationContext(), selfie.class);
        intent.putExtra("seasonyear",seasonyear.toString());
        intent.putExtra("plotnumber",plotnumber.toString());
        intent.putExtra("doctype",Integer.toString(doctype));
        startActivity(intent);
    }
}