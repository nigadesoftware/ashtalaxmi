package com.example.firstapp;

import android.content.Context;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.BaseAdapter;
import android.widget.TextView;

import java.util.ArrayList;

public class FieldsliplistbyvehicleAdapter extends BaseAdapter {
    private ArrayList<Fieldsliplistbyvehiclecode> listData;
    private LayoutInflater layoutInflater;
    public FieldsliplistbyvehicleAdapter(Context aContext, ArrayList<Fieldsliplistbyvehiclecode> listData) {
        this.listData = listData;
        layoutInflater = LayoutInflater.from(aContext);
    }
    @Override
    public int getCount() {
        return listData.size();
    }
    @Override
    public Object getItem(int position) {
        return listData.get(position);
    }
    @Override
    public long getItemId(int position) {
        return position;
    }
    public View getView(int position, View v, ViewGroup vg) {
        ViewHolder holder;
        if (v == null) {
            v = layoutInflater.inflate(R.layout.fieldsliplist_row, null);
            holder = new ViewHolder();
            holder.uFirstline = (TextView) v.findViewById(R.id.txvFirstline);
            holder.uSecondline = (TextView) v.findViewById(R.id.txvSecondline);
            holder.uThirdline = (TextView) v.findViewById(R.id.txvThirdline);
            v.setTag(holder);
        } else {
            holder = (ViewHolder) v.getTag();
        }
        holder.uFirstline.setText("फिल्डस्लिप नं:"+Integer.toString(listData.get(position).getfieldslipnumber())+" गाव:"+ listData.get(position).getfarmername().toString());
        holder.uSecondline.setText("प्लाॅट नं:"+listData.get(position).getplotnumber().toString()+" ट्रेलर: "+listData.get(position).getcontainername().toString());
        holder.uThirdline.setText("थर: "+listData.get(position).getlayername().toString());
        //holder.uSeasoncode.setText("प्लाॅट नं:"+Integer.toString(listData.get(position).getPlotnumber()));
        //holder.uFarmername.setText("गाव:");
        return v;
    }
    static class ViewHolder {
        TextView uFirstline;
        TextView uSecondline;
        TextView uThirdline;
    }
}