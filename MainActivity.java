package com.example.win7.hasck;

import android.content.Context;
import android.os.Bundle;
import android.support.v7.app.AppCompatActivity;
import android.support.v7.widget.LinearLayoutManager;
import android.support.v7.widget.RecyclerView;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.Menu;
import android.view.MenuItem;
import android.view.ViewGroup;
import android.widget.EditText;
import android.widget.TextView;
import android.widget.Toast;

import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.StringRequest;
import com.android.volley.toolbox.Volley;

import org.w3c.dom.Text;

import java.util.ArrayList;
import java.util.Arrays;
import java.util.HashMap;
import java.util.List;
import java.util.Map;

public class MainActivity extends AppCompatActivity {

    RequestQueue queue;
    String reponse = "";
    Integer nouvelle_mdc = 0;
    String nopce_memoire = "";
    String nom_memoire = "";
    String prenom_memoire = "";
    String email_client = "";
    String passe = "GtCu0Ps8dJN3";
    ArrayList<String> liste = new ArrayList<String>();
    ArrayList<String> liste_quantites = new ArrayList<String>();
    ArrayList<String> liste_courriels = new ArrayList<String>();
    String repreq = "";
    TextView tv_permanent;
    String montant_permanent="";

    int retrait_ou_commande = 0;
    String debut_adr = "https://test.";

    private RecyclerView mRecyclerView;
    private RecyclerView.Adapter mAdapter;
    private RecyclerView.LayoutManager mLayoutManager;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.content_main);

        queue = Volley.newRequestQueue(this);
        /*
        Toolbar toolbar = (Toolbar) findViewById(R.id.toolbar);
        setSupportActionBar(toolbar);

        FloatingActionButton fab = (FloatingActionButton) findViewById(R.id.fab);
        fab.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                Snackbar.make(view, "Replace with your own action", Snackbar.LENGTH_LONG)
                        .setAction("Action", null).show();
            }
        });
        */
    }

    @Override
    public boolean onCreateOptionsMenu(Menu menu) {
        // Inflate the menu; this adds items to the action bar if it is present.
        getMenuInflater().inflate(R.menu.menu_main, menu);
        return true;
    }

    @Override
    public boolean onOptionsItemSelected(MenuItem item) {
        // Handle action bar item clicks here. The action bar will
        // automatically handle clicks on the Home/Up button, so long
        // as you specify a parent activity in AndroidManifest.xml.
        int id = item.getItemId();

        //noinspection SimplifiableIfStatement
        if (id == R.id.action_settings) {
            return true;
        }

        return super.onOptionsItemSelected(item);
    }

    public boolean enregistrer_depot(View v) {
        setContentView(R.layout.layout_enrdep);
        return true;
    }

    public boolean pageAccueil(View v) {
        setContentView(R.layout.content_main);
        return true;
    }

    public boolean envoyerDonneesDepot(View v) {
        envoyerDonnesDepot2(0);
        return true;
    }

    public boolean envoyerDonnesDepot2(Integer memoriser_donnees) {
        EditText et_no = (EditText) findViewById(R.id.editText_no);
        EditText et_nom = (EditText) findViewById(R.id.editText_nom);
        EditText et_prenom = (EditText) findViewById(R.id.editText_prenom);
        EditText et_montant = (EditText) findViewById(R.id.editText_montant);
        String no_pce = et_no.getText().toString();
        String nom = et_nom.getText().toString();
        String prenom = et_prenom.getText().toString();
        String montant = et_montant.getText().toString();
        if (memoriser_donnees == 1) {
            nopce_memoire = no_pce;
            nom_memoire = nom;
            prenom_memoire = prenom;
        }

        if (!no_pce.equals("") && !nom.equals("") && !prenom.equals("") && !montant.equals("")) {
            Map<String, String> params = new HashMap<String, String>();
            params.put("Nopiece", no_pce);
            params.put("Nom", nom);
            params.put("Prenom", prenom);
            params.put("Montant", montant);
            params.put("Passe", passe);

            String url = debut_adr + "hasck.com/deposer.php";
            String fonction_suivante = "aucune";

            executerRequete(params, url, fonction_suivante);
        } else {
            Toast.makeText(this, "Donnée(s) manquante(s)", Toast.LENGTH_LONG).show();
        }
        return true;
    }

    public boolean mdcApresDepot(View v) {
        envoyerDonnesDepot2(1);
        nouvelle_mdc = 1;
        setContentView(R.layout.layout_mdc);
        miseDeCote2();
        return true;
    }

    public boolean miseDeCote(View v) {
        setContentView(R.layout.layout_mdc);
        nouvelle_mdc = 0;
        miseDeCote2();
        return true;
    }

    public boolean miseDeCote2() {
        final EditText et_nopce = (EditText) findViewById(R.id.editText_nopiece2);
        final EditText et_nom = (EditText) findViewById(R.id.editText_nom2);
        final EditText et_prenom = (EditText) findViewById(R.id.editText_prenom2);
        final TextView tv = (TextView) findViewById(R.id.textView_dispo);

        if (nouvelle_mdc == 1) {
            et_nopce.setText(nopce_memoire);
            et_nom.setText(nom_memoire);
            et_prenom.setText(prenom_memoire);
            afficherDispoDynamique(et_nopce, et_nom, et_prenom, tv);
        } else {
            //EditText et_prenom = ((EditText) findViewById(R.id.editText_prenom2));
            et_prenom.setOnFocusChangeListener(new View.OnFocusChangeListener() {

                @Override
                public void onFocusChange(View v, boolean hasFocus) {
                    //String rep="";

                    if (!hasFocus) afficherDispoDynamique(et_nopce, et_nom, et_prenom, tv);
                }
            });


        }
        return true;
    }


    public boolean afficherDispoDynamique(EditText et_nopce, EditText et_nom, EditText et_prenom, TextView tv) {
        String nopce = "";
        String nom = "";
        String prenom = "";
        tv_permanent = tv;

        if (nouvelle_mdc == 0) {
                        /*
                        EditText et_nopce = (EditText) findViewById(R.id.editText_nopiece2);
                        EditText et_nom = (EditText) findViewById(R.id.editText_nom2);
                        EditText et_prenom2 = (EditText) findViewById(R.id.editText_prenom2);
                        */
            nopce = et_nopce.getText().toString();
            nom = et_nom.getText().toString();
            prenom = et_prenom.getText().toString();
        } else {
            nopce = nopce_memoire;
            nom = nom_memoire;
            prenom = prenom_memoire;
        }
        //TextView tv = (TextView) findViewById(R.id.textView_dispo);

        //v=et_prenom2;
        if (!nopce.equals("") && !nom.equals("") && !prenom.equals("")) {
            Map<String, String> params = new HashMap<String, String>();
            params.put("Nopiece", nopce);
            params.put("Nom", nom);
            params.put("Prenom", prenom);

            String url = debut_adr + "hasck.com/dispo.php";

            String fonction_suivante = "afficheDisponible";
            executerRequete(params, url, fonction_suivante);

            //return et_prenom2;
        }

        return true;
    }

    public void afficheDisponible(String rep) {
        String montant = "";
        Integer debut = 0;
        Integer fin = 0;

        String dispo = "Disponible :";
        String err_trop_util = "Erreur, trop";
        String err_zero_util = "Erreur, aucun";
        if (rep.toLowerCase().contains(dispo.toLowerCase())) {
            debut = rep.indexOf(dispo) + 12;
            fin = rep.indexOf("</body>") - 1;
            System.out.println("debut : "+debut + "\nfin : "+fin);
            montant = rep.substring(debut, fin);
        } else {
            if (rep.toLowerCase().contains(err_trop_util.toLowerCase())) {
                montant = "0, plusieurs utilisateurs correspondent";
            } else {
                if (rep.toLowerCase().contains(err_zero_util.toLowerCase())) {
                    montant = "0, aucun utilisateur ne correspond";
                }
            }
        }
        tv_permanent.setText(montant);
    }

    public void nouvelleMiseCote(View v) {
        EditText et_nopce = (EditText) findViewById(R.id.editText_nopiece2);
        EditText et_nom = (EditText) findViewById(R.id.editText_nom2);
        EditText et_prenom = (EditText) findViewById(R.id.editText_prenom2);
        String nopce = et_nopce.getText().toString();
        String nom = et_nom.getText().toString();
        String prenom = et_prenom.getText().toString();
        effectuerMDC();
        suiteMiseCote(nopce, nom, prenom);
    }

    public void suiteMiseCote(String nopce, String nom, String prenom) {
        ViewGroup vg;
        vg = findViewById(R.id.linearLayout);
        vg.invalidate();
        nopce_memoire = nopce;
        nom_memoire = nom;
        prenom_memoire = prenom;
        nouvelle_mdc = 1;
        miseDeCote2();
    }

    public void Terminer(View v) {
        nouvelle_mdc = 0;
        effectuerMDC();
    }

    public void effectuerMDC() {
        EditText et_nopce = (EditText) findViewById(R.id.editText_nopiece2);
        EditText et_nom = (EditText) findViewById(R.id.editText_nom2);
        EditText et_prenom = (EditText) findViewById(R.id.editText_prenom2);
        EditText et_montant = (EditText) findViewById(R.id.editText_montant2);
        EditText et_ref = (EditText) findViewById(R.id.editText_refprod);
        String nopce = et_nopce.getText().toString();
        String nom = et_nom.getText().toString();
        String prenom = et_prenom.getText().toString();
        String montant = et_montant.getText().toString();
        String ref = et_ref.getText().toString();
        String no_prod = "montant" + ref;

        if (!nopce.equals("") && !nom.equals("") && !prenom.equals("") && !montant.equals("") && !ref.equals("")) {

            Map<String, String> params = new HashMap<String, String>();
            params.put("num_id", nopce);
            params.put("nom", nom);
            params.put("prenom", prenom);
            params.put(no_prod, montant);
            params.put("bogus", "0");

            String url = debut_adr + "hasck.com/mettrecote.php";
            String fonction_suivante = "afficheMDC";

            final String rep = executerRequete(params, url, fonction_suivante);

        } else {
            Toast.makeText(this, "Donnée(s) manquante(s)", Toast.LENGTH_LONG).show();
        }
    }

    public void afficheMDC(String rep) {
        String nbmisescote = "Nombre de";
        if (rep.toLowerCase().contains(nbmisescote.toLowerCase())) {
            Integer debut = rep.indexOf(nbmisescote) + 25;
            Integer fin = rep.indexOf("</body>") - 1;
            System.out.println("debut : "+debut + "\nfin : "+fin);
            String nbmises = "Nombre de mises de côté : " + rep.substring(debut, fin);
            Toast.makeText(this, nbmises, Toast.LENGTH_LONG).show();
        }

    }

    public boolean enregistrerAchat(View v) {
        setContentView(R.layout.layout_achat);
        liste.clear();
        liste_quantites.clear();
        liste_courriels.clear();
        email_client = "";
        //liste=new ArrayList<String>();

        return true;
    }

    public boolean produitSuivant(View v) {
        Toast.makeText(this, "Début de produitSuivant", Toast.LENGTH_LONG).show();
        EditText et_prod = (EditText) findViewById(R.id.editText_refachat);
        EditText et_email = (EditText) findViewById(R.id.editText_email);
        EditText et_qte = (EditText) findViewById(R.id.editText_qteachetee);
        String email = et_email.getText().toString();
        String quantite = et_qte.getText().toString();
        if (!email.equals("") && email_client.equals("")) {
            et_email.setText(email);
            email_client = email;
        } else {
            if (email.equals("") && !email_client.equals("")) {
                et_email.setText(email_client);
            }
        }
        String ref = et_prod.getText().toString();
        if (!ref.equals("")) {
            liste.add(ref);
            if (quantite.equals("")) quantite = "1";
            liste_quantites.add(quantite);
            liste_courriels.add(email);
            ViewGroup vg;
            vg = findViewById(R.id.linearLayout3);
            vg.invalidate();
            afficherLayoutAchat();
        } else {
            Toast.makeText(this, "La référence du produit est nécessaire", Toast.LENGTH_LONG).show();
        }
        return true;
    }

    public boolean afficherLayoutAchat() {
        setContentView(R.layout.layout_achat);
        return true;
    }

    public boolean finAchat(View v) {
        int i = 0;
        String idprod = "";

        EditText et_email = (EditText) findViewById(R.id.editText_email);
        EditText et_qte = (EditText) findViewById(R.id.editText_qteachetee);
        EditText et_ref = (EditText) findViewById(R.id.editText_refachat);
        String email = et_email.getText().toString();
        String qte = et_qte.getText().toString();
        String refprod = et_ref.getText().toString();
        if (qte.equals("")) {
            qte = "1";
            Toast.makeText(this, "Aucune quantité entrée, quantité retenue : 1", Toast.LENGTH_LONG).show();
        }
        if (!refprod.equals("")) {
            liste.add(refprod);
            liste_quantites.add(qte);
            liste_courriels.add(email);
        }
        String email2 = "";
        if ((!email.equals("") || !email_client.equals(""))) {
            for (i = 0; i < liste.size(); i++) {
                idprod = liste.get(i);
                qte = liste_quantites.get(i);
                email2 = liste_courriels.get(i);
                if (!qte.equals("") && !qte.equals("0")) {
                    Map<String, String> params = new HashMap<String, String>();
                    params.put("bogus", "0");
                    params.put("Mail", email2);
                    params.put("idprod", idprod);
                    params.put("quantite", qte);

                    String url = debut_adr + "hasck.com/exec_acheter.php";
                    String fonction_suivante="aucune";

                    final String rep = executerRequete(params, url,fonction_suivante);
                }
            }
            liste.clear();
            liste_quantites.clear();
            liste_courriels.clear();
            setContentView(R.layout.content_main);
        } else {
            Toast.makeText(this, "Le courriel du client qui commande est nécessaire", Toast.LENGTH_LONG).show();
        }
        return true;
    }

    public boolean demanderRetrait(View v) {
        setContentView(R.layout.layout_retrait);
        nouvelle_mdc = 0;
        final EditText et_nopce = (EditText) findViewById(R.id.editText_nopceret);
        final EditText et_nom = (EditText) findViewById(R.id.editText_nomret);
        final EditText et_prenom = (EditText) findViewById(R.id.editText_prenomret);
        final TextView tv = (TextView) findViewById(R.id.textView_disporet);
        et_prenom.setOnFocusChangeListener(new View.OnFocusChangeListener() {

            @Override
            public void onFocusChange(View v, boolean hasFocus) {
                //String rep="";

                if (!hasFocus) afficherDispoDynamique(et_nopce, et_nom, et_prenom, tv);
            }
        });
        return true;
    }

    public boolean validerRetrait(View v) {
        EditText et_nopce = (EditText) findViewById(R.id.editText_nopceret);
        EditText et_nom = (EditText) findViewById(R.id.editText_nomret);
        EditText et_prenom = (EditText) findViewById(R.id.editText_prenomret);
        EditText et_montant = (EditText) findViewById(R.id.editText_montanret);
        String nopce = et_nopce.getText().toString();
        String nom = et_nom.getText().toString();
        String prenom = et_prenom.getText().toString();
        String montant = et_montant.getText().toString();
        montant_permanent=montant;

        if (!nopce.equals("") && !nom.equals("") && !prenom.equals("") && !montant.equals("")) {
            Map<String, String> params = new HashMap<String, String>();
            params.put("bogus", "0");
            params.put("No_piece", nopce);
            params.put("Nom", nom);
            params.put("Prenom", prenom);

            String url = debut_adr + "hasck.com/trouve_util.php";
            String fonction_suivante = "numeroUtilisateur";

            final String rep = executerRequete(params, url, fonction_suivante);

            //setContentView(R.layout.content_main);
        } else {
            Toast.makeText(this, "Donnée(s) manquante(s)", Toast.LENGTH_LONG).show();
        }
        return true;
    }

    public void numeroUtilisateur(String rep) {

        Integer debut, fin;
        String identif = "Identifiant :";
        String id = "";
        if(rep.toLowerCase().contains(identif.toLowerCase()))
        {
            debut = rep.indexOf(identif) + 12;
            fin = rep.indexOf("</body>") - 1;
            System.out.println("debut : "+debut + "\nfin : "+fin);
            id = rep.substring(debut, fin);

            Map<String, String> params2 = new HashMap<String, String>();
            params2.put("bogus", "0");
            params2.put("id", id);
            String montant=montant_permanent;
            params2.put("montant_ret", montant);

            String url2 = debut_adr + "hasck.com/dem_retrait.php";
            String fonction_suivante="aucune";

            final String rep2 = executerRequete(params2, url2,fonction_suivante);
        }
        else {
            Toast.makeText(this, "Utilisateur non trouvé", Toast.LENGTH_LONG).show();
        }

    }


    public boolean retraitEffectue(View v){
        //Toast.makeText(this,"début de retraitEffectue",Toast.LENGTH_LONG).show();
        setContentView(R.layout.layout_retreff);
        retrait_ou_commande=0;
        mRecyclerView = (RecyclerView) findViewById(R.id.my_recycler_view);
        mLayoutManager = new LinearLayoutManager(this);
        mRecyclerView.setLayoutManager(mLayoutManager);

        Map<String, String> params = new HashMap<String, String>();
        String url = debut_adr+"hasck.com/liste_retraitsfuturs.php";
        String fonction_suivante="afficherRecView";

        final String rep = executerRequete(params, url,fonction_suivante);


        //Toast.makeText(this,"fin de retraitEffectue",Toast.LENGTH_LONG).show();

        return true;
    }

    public void afficherRecView(String rep){
        Integer debut=rep.indexOf("<body>")+6;
        Integer fin=rep.indexOf("</body>")-1;
        System.out.println("retraitEffectue, reponse : "+rep);
        System.out.println("debut : "+debut + "\nfin : "+fin);
        String corps="";
        if(debut<fin) corps=rep.substring(debut,fin);
        System.out.println("corps : "+corps);

        List<String> al= Arrays.asList(corps.split(";"));
        int nb_lignes=al.size();
        System.out.println("nb_lignes : "+nb_lignes);

        ArrayList<String> myDataset=new ArrayList<String>();
        //myDataset.add("Ligne 1");
        //myDataset.add("Ligne 2");
        for(int i=0;i<nb_lignes;i++){
            String ligne=al.get(i);
            //ligne.replace(",","\n");
            myDataset.add(ligne);
        }

        //Toast.makeText(this,"milieu de retraitEffectue",Toast.LENGTH_LONG).show();

        // specify an adapter (see also next example)
        mAdapter = new MyAdapter(myDataset);
        mRecyclerView.setAdapter(mAdapter);

    }

    public boolean confirmerRetrait(View v){
        if(retrait_ou_commande==0) {
            TextView tv_pce = (TextView) findViewById(R.id.textView_pceconf);
            TextView tv_nom = (TextView) findViewById(R.id.textView_nomconf);
            TextView tv_prenom = (TextView) findViewById(R.id.textView_prenomconf);
            TextView tv_montant = (TextView) findViewById(R.id.textView_montantconf);
            String no_pce = tv_pce.getText().toString();
            String nom = tv_nom.getText().toString();
            String prenom = tv_prenom.getText().toString();
            String montant = tv_montant.getText().toString();

            if (!no_pce.equals("") && !nom.equals("") && !prenom.equals("") && !montant.equals("")) {

                Map<String, String> params = new HashMap<String, String>();
                params.put("No_piece", no_pce);
                params.put("Nom", nom);
                params.put("Prenom", prenom);
                params.put("Montant", montant);

                String url = debut_adr+"hasck.com/confirme_retrait.php";
                String fonction_suivante="aucune";

                final String rep = executerRequete(params, url,fonction_suivante);

                setContentView(R.layout.content_main);
            }
            else{
                Toast.makeText(this, "Donnée(s) manquante(s)", Toast.LENGTH_LONG).show();
            }
        }
        else{
            TextView tv_idcomm = (TextView) findViewById(R.id.textView_pceconf);
            TextView tv_qteremise = (TextView) findViewById(R.id.textView_montantconf);
            String idcomm = tv_idcomm.getText().toString();
            String qteremise = tv_qteremise.getText().toString();

            if (!idcomm.equals("") && !qteremise.equals("")) {

                Map<String, String> params2 = new HashMap<String, String>();
                params2.put("idcomm", idcomm);
                params2.put("qteremise", qteremise);

                String url2 = debut_adr+"hasck.com/confirme_livraison.php";
                String fonction_suivante2="aucune";

                final String rep = executerRequete(params2, url2,fonction_suivante2);

                setContentView(R.layout.content_main);
            }
            else{
                Toast.makeText(this, "Donnée(s) manquante(s)", Toast.LENGTH_LONG).show();
            }
        }
        return true;
    }

    public boolean aReverser(View v){
        Map<String, String> params = new HashMap<String, String>();

        String url = debut_adr+"hasck.com/retraits_pendants.php";
        String fonction_suivante="afficheAReverser";

        final String rep = executerRequete(params, url,fonction_suivante);

        return true;
    }

    public void afficheAReverser(String rep){
        String interessant="A reverser :";
        String terminaison="fin du message";
        int debut,fin;
        String montant="inconnu";

        System.out.println("rep to lower case : "+rep.toLowerCase());
        System.out.println("interessant to lower case : "+interessant.toLowerCase());
        System.out.println("terminaison to lower case : "+terminaison.toLowerCase());


        if(rep.toLowerCase().contains(interessant.toLowerCase()) && rep.toLowerCase().contains(terminaison.toLowerCase())){
            System.out.println("Dans branchement de aReverser");
            debut=rep.indexOf(interessant)+11;
            fin=rep.indexOf(terminaison)-2;
            System.out.println("debut : "+debut + "\nfin : "+fin);
            montant=rep.substring(debut,fin);
            //System.out.println("debut : "+debut+"\nfin : "+fin);

        }
        setContentView(R.layout.layout_areverser);
        TextView tv=(TextView) findViewById(R.id.textView_montantrev);
        tv.setText(montant);

    }

    public boolean livrerCommande(View v){
        setContentView(R.layout.layout_commandes);
        return true;
    }

    public boolean recupererCommandes(View v){
        retrait_ou_commande=1;
        EditText et_email=(EditText) findViewById(R.id.editText_emailcom);
        String email=et_email.getText().toString();

        if(!email.equals("")){
            Map<String, String> params = new HashMap<String, String>();
            params.put("Courriel", email);

            String url = debut_adr+"hasck.com/recupere_commandes.php";
            String fonction_suivante="afficherRecViewComm";

            final String rep = executerRequete(params, url,fonction_suivante);

        }
        else{
            Toast.makeText(this,"Le courriel du client est nécessaire",Toast.LENGTH_LONG).show();
        }

        return true;
    }

    public void afficherRecViewComm(String rep){
        int debut,fin;
        String debut_mess="debut du message:";
        String fin_mess=":fin du message";
        if(rep.toLowerCase().contains(debut_mess.toLowerCase()) && rep.toLowerCase().contains(fin_mess.toLowerCase())){
            debut=rep.indexOf(debut_mess)+17;
            fin=rep.indexOf(fin_mess)-1;
            System.out.println("debut : "+debut + "\nfin : "+fin);
            String commandes=rep.substring(debut,fin);
            System.out.println("commandes : "+commandes);

            //Toast.makeText(this,"récupérerCommandes, avant RecyclerV",Toast.LENGTH_LONG).show();

            //Affichage de la liste des commandes sous forme de RecyclerView
            setContentView(R.layout.layout_retreff);
            mRecyclerView = (RecyclerView) findViewById(R.id.my_recycler_view);
            mLayoutManager = new LinearLayoutManager(this);
            mRecyclerView.setLayoutManager(mLayoutManager);

            List<String> al= Arrays.asList(commandes.split(";"));
            int nb_lignes=al.size();
            System.out.println("nb_lignes : "+nb_lignes);
            ArrayList<String> myDataset=new ArrayList<String>();
            for(int i=0;i<nb_lignes;i++){
                String ligne=al.get(i);
                //ligne.replace(",","\n");
                myDataset.add(ligne);
            }
            //Toast.makeText(this,"récupérerCommandes, avant Adapter",Toast.LENGTH_LONG).show();

            // specify an adapter (see also next example)
            mAdapter = new MyAdapter(myDataset);
            mRecyclerView.setAdapter(mAdapter);
            //Toast.makeText(this,"récupérerCommandes, après setAdapter",Toast.LENGTH_LONG).show();
        }

    }

    public boolean confirme(View v){
        TextView tv=(TextView) v.findViewById(R.id.text_view_liste) ;
        String contenu=tv.getText().toString();
        System.out.println("contenu dans confirme : "+contenu);
        //List<String> liste= Arrays.asList(contenu.split("\n"));
        List<String> liste= Arrays.asList(contenu.split(","));
        String nopiece_ou_idcomm=liste.get(0);
        String nom_ou_idprod=liste.get(1);
        String prenom_ou_nomprod=liste.get(2);
        String montant_ou_qtelivrable=liste.get(3);

        setContentView(R.layout.layout_confirme);

        TextView tv_piece_idcomm=(TextView) findViewById(R.id.textView_pceconf);
        tv_piece_idcomm.setText(nopiece_ou_idcomm);
        TextView tv_nom_idprod=(TextView) findViewById(R.id.textView_nomconf);
        tv_nom_idprod.setText(nom_ou_idprod);
        TextView tv_prenom_nomprod=(TextView) findViewById(R.id.textView_prenomconf);
        tv_prenom_nomprod.setText(prenom_ou_nomprod);
        TextView tv_montant_livrable=(TextView) findViewById(R.id.textView_montantconf);
        tv_montant_livrable.setText(montant_ou_qtelivrable);
        return true;
    }

    public void annuleCommande(View v){
        setContentView(R.layout.layout_annulecom);
    }

    public boolean enregAnnulation(View v){
        EditText et_ann=(EditText) findViewById(R.id.editText_nocommannule);
        String no_comm=et_ann.getText().toString();

        Map<String, String> params = new HashMap<String, String>();
        params.put("idcomm", no_comm);

        String url = debut_adr+"hasck.com/exec_annule.php";
        String fonction_suivante="confirmationAnnulation";

        return true;
    }

    public void confirmationAnnulation(String rep){
        String annulation="Annulation reussie";
        setContentView(R.layout.layout_message);
        TextView tv=(TextView) findViewById(R.id.textView_message);
        if(rep.toLowerCase().contains(annulation.toLowerCase())){
            tv.setText(annulation);
        }
        else{
            tv.setText("Annulation échouée");
        }
    }

    public boolean enregistrerRetour(View v){
        setContentView(R.layout.layout_pendantsouimmediat);
        return true;
    }

    public boolean retourParNoComm(View v){
        EditText et_nocomm=(EditText) findViewById(R.id.editText_nocommret);
        String no_comm=et_nocomm.getText().toString();

        Map<String, String> params = new HashMap<String, String>();
        params.put("idcomm", no_comm);

        String url = debut_adr+"hasck.com/retour_nocomm.php";
        String fonction_suivante="aucune";

        return true;
    }

    public boolean retoursPendants(View v){

        Map<String, String> params = new HashMap<String, String>();
        params.put("rien", "rien");

        String url = debut_adr+"hasck.com/retours_pendants.php";
        String fonction_suivante="afficherRecViewRetours";

        return true;
    }

    public void afficherRecViewRetours(String rep){
        setContentView(R.layout.layout_retreff);
        mRecyclerView = (RecyclerView) findViewById(R.id.my_recycler_view);
        mLayoutManager = new LinearLayoutManager(this);
        mRecyclerView.setLayoutManager(mLayoutManager);

        Integer debut,fin;
        debut=rep.indexOf("<body>")+6;
        fin=rep.indexOf("</body>")-1;
        String retours=rep.substring(debut,fin);

        List<String> al= Arrays.asList(retours.split(";"));
        int nb_lignes=al.size();
        System.out.println("nb_lignes : "+nb_lignes);
        ArrayList<String> myDataset=new ArrayList<String>();
        for(int i=0;i<nb_lignes;i++){
            String ligne=al.get(i);
            //ligne.replace(",","\n");
            myDataset.add(ligne);
        }
        //Toast.makeText(this,"récupérerCommandes, avant Adapter",Toast.LENGTH_LONG).show();

        // specify an adapter (see also next example)
        mAdapter = new AdapterRetours(myDataset);
        mRecyclerView.setAdapter(mAdapter);
    }

    public void confirmeRetour(View v){
        TextView tv_retour=(TextView) v.findViewById(R.id.text_view_listeretours);
        String retour=tv_retour.getText().toString();

        List<String> liste= Arrays.asList(retour.split(","));
        String idcomm=liste.get(0);
        String idprod=liste.get(1);
        String nom_prod=liste.get(2);
        String livree_client=liste.get(3);
        String email_client=liste.get(4);

        setContentView(R.layout.layout_confirmeret);
        TextView tv_idcomm=(TextView) findViewById(R.id.textView_idcommretour);
        tv_idcomm.setText(idcomm);
        TextView tv_idprod=(TextView) findViewById(R.id.textView_idprodretour);
        tv_idprod.setText(idprod);
        TextView tv_nomprod=(TextView) findViewById(R.id.textView_nomprodretour);
        tv_nomprod.setText(nom_prod);
        TextView tv_aretourner=(TextView) findViewById(R.id.textView_qtearetourner);
        tv_aretourner.setText(livree_client);
        TextView tv_email=(TextView) findViewById(R.id.textView_emailclientret);
        tv_email.setText(email_client);
    }

    public void validerRetour(View v){
        TextView tv_idcomm=(TextView) findViewById(R.id.textView_idcommretour);
        String idcomm=tv_idcomm.getText().toString();

        Map<String, String> params = new HashMap<String, String>();
        params.put("idcomm", idcomm);

        String url = debut_adr+"hasck.com/retour_nocomm.php";
        String fonction_suivante="aucune";

    }

    public boolean enregistrerRemboursement(View v){
        Map<String, String> params = new HashMap<String, String>();
        params.put("rien", "rien");

        String url = debut_adr+"hasck.com/arembourser.php";
        String fonction_suivante="afficherRecViewRemb";
        return true;
    }

    public void afficherRecViewRemb(String rep){
        setContentView(R.layout.layout_retreff);
        mRecyclerView = (RecyclerView) findViewById(R.id.my_recycler_view);
        mLayoutManager = new LinearLayoutManager(this);
        mRecyclerView.setLayoutManager(mLayoutManager);

        Integer debut,fin;
        debut=rep.indexOf("<body>")+6;
        fin=rep.indexOf("</body>")-1;
        String remboursements=rep.substring(debut,fin);

        List<String> al= Arrays.asList(remboursements.split(";"));
        int nb_lignes=al.size();
        System.out.println("nb_lignes : "+nb_lignes);
        ArrayList<String> myDataset=new ArrayList<String>();
        for(int i=0;i<nb_lignes;i++){
            String ligne=al.get(i);
            //ligne.replace(",","\n");
            myDataset.add(ligne);
        }
        //Toast.makeText(this,"récupérerCommandes, avant Adapter",Toast.LENGTH_LONG).show();

        // specify an adapter (see also next example)
        mAdapter = new AdapterRemb(myDataset);
        mRecyclerView.setAdapter(mAdapter);
    }

    public void confirmerRemboursement(View v){
        TextView tv_remb=(TextView) v.findViewById(R.id.text_view_listeremb);
        String remboursement=tv_remb.getText().toString();

        List<String> liste= Arrays.asList(remboursement.split(","));
        String idcomm=liste.get(0);
        String montant=liste.get(1);

        setContentView(R.layout.layout_confirmeremb);
        TextView tv_idcomm=(TextView) findViewById(R.id.textView_nocommremb);
        tv_idcomm.setText(idcomm);
        TextView tv_montant=(TextView) findViewById(R.id.textView_montantremb);
        tv_montant.setText(montant);
    }

    public void remboursementEffectue(View v) {
        TextView tv_idcomm = (TextView) findViewById(R.id.textView_idcommretour);
        String idcomm = tv_idcomm.getText().toString();

        Map<String, String> params = new HashMap<String, String>();
        params.put("idcomm", idcomm);

        String url = debut_adr + "hasck.com/enreg_remb.php";
        String fonction_suivante = "aucune";
    }

    public String executerRequete(final Map<String, String> params, String url, final String fonction_suivante){
        //final String reponse="";
        reponse="";
        StringRequest postRequest = new StringRequest(Request.Method.POST, url,
                new Response.Listener<String>() {
            @Override
            public void onResponse(String response) {
                reponse=response;
                Log.d("Response",response);
                System.out.println("reponse : "+response);
                switch(fonction_suivante){
                    case "afficheDisponible" : afficheDisponible(response);
                    break;
                    case "afficheMDC" : afficheMDC(response);
                    break;
                    case "numeroUtilisateur" : numeroUtilisateur(response);
                    break;
                    case "afficherRecView" : afficherRecView(response);
                    break;
                    case "afficheAReverser" : afficheAReverser(response);
                    break;
                    case "afficherRecViewComm" : afficherRecViewComm(response);
                    break;
                    case "confirmationAnnulation" : confirmationAnnulation(response);
                    break;
                    case "afficherRecViewRetours" : afficherRecViewRetours(response);
                    break;
                    case "afficherRecViewRemb" : afficherRecViewRemb(response);
                    break;
                    default :
                        break;
                }
                //repreq=response;
                //setContentView(R.layout.layout_reponse);
                //TextView tv=(TextView) findViewById(R.id.textView_reponse);
                //tv.setText(response);
            }
        },
                new Response.ErrorListener(){
                    @Override
                    public void onErrorResponse(VolleyError error){
                        Log.d("Error.Response","Bug dans executerRequete");
                    }
                })
        {
            @Override
            protected Map<String,String> getParams(){
                return params;
            }
        };
        queue.add(postRequest);
        return reponse;
    }
}
