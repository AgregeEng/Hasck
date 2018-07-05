package com.example.win7.hasck;

import android.support.v7.widget.RecyclerView;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.TextView;

import java.util.ArrayList;

class AdapterRetours extends RecyclerView.Adapter<AdapterRetours.ViewHolder> {
    ArrayList<String> mDataset;
    int retrait_ou_comm=0;


    // Provide a reference to the views for each data item
    // Complex data items may need more than one view per item, and
    // you provide access to all the views for a data item in a view holder
    public static class ViewHolder extends RecyclerView.ViewHolder {
        // each data item is just a string in this case
        public TextView mTextView;
        public ViewHolder(View v) {
            super(v);
            mTextView = v.findViewById(R.id.text_view_listeretours);

            /*
            mTextView.setClickable(true);
            mTextView.setOnClickListener(new View.OnClickListener() {
                @Override
                public void onClick(View v) {
                    System.out.println("clic dans le RecyclerView");
                    String contenu=mTextView.getText().toString();
                    //List<String> liste= Arrays.asList(contenu.split("\n"));
                    List<String> liste= Arrays.asList(contenu.split(","));
                    String nopiece_ou_idcomm=liste.get(0);
                    //String reste=liste.get(1);
                    //List<String> liste2= Arrays.asList(reste.split(","));
                    //String nom_ou_idprod=liste2.get(0);
                    //String prenom_ou_nomprod=liste2.get(1);
                    //String montant_ou_qtelivrable=liste2.get(2);

                    String nom_ou_idprod=liste.get(1);
                    String prenom_ou_nomprod=liste.get(2);
                    String montant_ou_qtelivrable=liste.get(3);

                    LayoutInflater li=(LayoutInflater) v.getContext().getSystemService(Context.LAYOUT_INFLATER_SERVICE);
                    View v2=li.inflate(R.layout.layout_confirme,null);

                    TextView tv_piece_idcomm=v2.findViewById(R.id.textView_pceconf);
                    tv_piece_idcomm.setText(nopiece_ou_idcomm);
                    TextView tv_nom_idprod=v2.findViewById(R.id.textView_nomconf);
                    tv_nom_idprod.setText(nom_ou_idprod);
                    TextView tv_prenom_nomprod=v2.findViewById(R.id.textView_prenomconf);
                    tv_prenom_nomprod.setText(prenom_ou_nomprod);
                    TextView tv_montant_livrable=v2.findViewById(R.id.textView_montantconf);
                    tv_montant_livrable.setText(montant_ou_qtelivrable);

                }
            });
            */

        }
    }


    public AdapterRetours(ArrayList<String> myDataSet) {
        mDataset=myDataSet;
    }

    // Create new views (invoked by the layout manager)
    @Override
    public AdapterRetours.ViewHolder onCreateViewHolder(ViewGroup parent,
                                                   int viewType) {
        // create a new view
        View v = LayoutInflater.from(parent.getContext())
                .inflate(R.layout.layout_listeretours, parent, false);

        ViewHolder vh = new ViewHolder(v);
        return vh;
    }

    // Replace the contents of a view (invoked by the layout manager)
    @Override
    public void onBindViewHolder(ViewHolder holder, int position) {
        // - get element from your dataset at this position
        // - replace the contents of the view with that element
        System.out.println("tour dans onBindViewHolder");
        holder.mTextView.setText(mDataset.get(position));

    }

    // Return the size of your dataset (invoked by the layout manager)
    @Override
    public int getItemCount() {
        System.out.println("taille de la liste : "+mDataset.size());
        return mDataset.size();
    }


}
