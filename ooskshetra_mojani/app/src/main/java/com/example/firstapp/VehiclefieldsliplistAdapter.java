package com.example.firstapp;

        import android.content.Context;
        import android.view.LayoutInflater;
        import android.view.View;
        import android.view.ViewGroup;
        import android.widget.BaseAdapter;
        import android.widget.TextView;

        import java.util.ArrayList;

public class VehiclefieldsliplistAdapter extends BaseAdapter {
    private ArrayList<Vehiclefieldsliplist> listData;
    private LayoutInflater layoutInflater;
    public VehiclefieldsliplistAdapter(Context aContext, ArrayList<Vehiclefieldsliplist> listData) {
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
            v = layoutInflater.inflate(R.layout.vehiclelist_row, null);
            holder = new ViewHolder();
            holder.uVehicle = (TextView) v.findViewById(R.id.txvvehicle);

            v.setTag(holder);
        } else {
            holder = (ViewHolder) v.getTag();
        }
        holder.uVehicle.setText(" वाहन :"+ listData.get(position).getVehiclecategory().toString()+" नंबर:"+listData.get(position).getVehiclenumber().toString()+" स्लिप संख्या:"+Integer.toString(listData.get(position).getSlipcount()));
        //holder.uSeasoncode.setText("प्लाॅट नं:"+Integer.toString(listData.get(position).getPlotnumber()));
        //holder.uFarmername.setText("गाव:");
        return v;
    }
    static class ViewHolder {
        TextView uVehicle;
    }
}