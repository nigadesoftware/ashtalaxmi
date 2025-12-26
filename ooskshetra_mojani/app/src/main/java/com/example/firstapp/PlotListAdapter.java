package com.example.firstapp;

import android.content.Context;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.BaseAdapter;
import android.widget.TextView;

import java.util.ArrayList;

public class PlotListAdapter extends BaseAdapter {
    private ArrayList<PlotList> listData;
    private LayoutInflater layoutInflater;
    public PlotListAdapter(Context aContext, ArrayList<PlotList> listData) {
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
            v = layoutInflater.inflate(R.layout.plotlist_row, null);
            holder = new ViewHolder();
            holder.uSeasoncode = (TextView) v.findViewById(R.id.txvseasoncode);
            holder.uFarmername = (TextView) v.findViewById(R.id.txvfarmername);

            v.setTag(holder);
        } else {
            holder = (ViewHolder) v.getTag();
        }
        holder.uSeasoncode.setText("प्लाॅट नं:"+Integer.toString(listData.get(position).getPlotnumber())+" सर्वे/गट नं:"+ listData.get(position).getGatsurveno().toString()+" हंगाम:"+Integer.toString(listData.get(position).getSeasoncode()));
        holder.uFarmername.setText("गाव:"+listData.get(position).getVillagename().toString()+" शेतकरी: "+listData.get(position).getFarmername().toString());
        return v;
    }
    static class ViewHolder {
        TextView uSeasoncode;
        TextView uFarmername;
    }
}