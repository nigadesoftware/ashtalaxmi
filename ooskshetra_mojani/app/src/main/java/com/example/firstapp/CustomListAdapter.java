package com.example.firstapp;

import android.content.Context;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.BaseAdapter;
import android.widget.TextView;

import java.util.ArrayList;
/**
 * Created by tutlane on 23-08-2017.
 */
public class CustomListAdapter extends BaseAdapter {
    private ArrayList<ListItem> listData;
    private LayoutInflater layoutInflater;
    public CustomListAdapter(Context aContext, ArrayList<ListItem> listData) {
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
            v = layoutInflater.inflate(R.layout.list_row, null);
            holder = new ViewHolder();
            holder.uSerialnumber = (TextView) v.findViewById(R.id.txvserialnumber);
            holder.uLatitude = (TextView) v.findViewById(R.id.txvlatitude);
            //holder.uLongitude = (TextView) v.findViewById(R.id.txvlongitude);
            holder.uDistance = (TextView) v.findViewById(R.id.txvdistance);
            v.setTag(holder);
        } else {
            holder = (ViewHolder) v.getTag();
        }
        holder.uSerialnumber.setText("बिंदू : "+Integer.toString(listData.get(position).getSerialnumber()));
        holder.uLatitude.setText("अक्षांश : "+Double.toString(listData.get(position).getLatitude())+" रेखांश : "+Double.toString(listData.get(position).getLongitude()));
        //holder.uLongitude.setText("Longitude : "+Double.toString(listData.get(position).getLongitude()));
        holder.uDistance.setText("अंतर : "+Double.toString(listData.get(position).getDistance()));
        return v;
    }
    static class ViewHolder {
        TextView uSerialnumber;
        TextView uLatitude;
        //TextView uLongitude;
        TextView uDistance;
    }
}