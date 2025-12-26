package com.example.firstapp;

import androidx.appcompat.app.AppCompatActivity;

import android.os.Bundle;
import android.widget.TextView;

public class second_activity extends AppCompatActivity {
    private TextView tvName, tvPass;
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_second_activity);
        tvName = findViewById(R.id.tvusername);
        tvPass = findViewById(R.id.tvpassword);

        tvName.setText(MainActivity.publicFirstName);
        tvPass.setText(MainActivity.publicHobby);
    }
}
