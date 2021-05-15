<!--<footer class="main-footer">
    <strong> Yves LADDE - Tout droits réservés <a href="https://ladde.000webhostapp.com">Site web</a>.</strong>
</footer>-->

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="plugins/jquery-ui/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
    $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- ChartJS -->
<script src="plugins/chart.js/Chart.min.js"></script>
<!-- Sparkline -->
<script src="plugins/sparklines/sparkline.js"></script>
<!-- JQVMap -->
<script src="plugins/jqvmap/jquery.vmap.min.js"></script>
<script src="plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
<!-- jQuery Knob Chart -->
<script src="plugins/jquery-knob/jquery.knob.min.js"></script>
<!-- daterangepicker -->
<script src="plugins/moment/moment.min.js"></script>
<script src="plugins/daterangepicker/daterangepicker.js"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<!-- Summernote -->
<script src="plugins/summernote/summernote-bs4.min.js"></script>
<!-- overlayScrollbars -->
<script src="plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="dist/js/demo.js"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="dist/js/pages/dashboard.js"></script>

<script>

    $(function() {
        console.log( "ready!" );
        fermer_tous_les_garde_fou();
    });


    function toggle_garde_fou(id){
        var le_garde_fou = document.getElementById('garde_fou_'+id);
        if(le_garde_fou.style.display != 'none'){
            le_garde_fou.style.display = 'none';
        }else{
            le_garde_fou.style.display = 'block';
        }
    }

    function  fermer_tous_les_garde_fou(){
        var les_garde_fous = document.getElementsByClassName('garde_fou');
        for(var i=0;i<les_garde_fous.length;i++){
            les_garde_fous[i].style.display='none';
        }
    }

    function calcul_prix_total_ditem(id){

        var qte = document.getElementById("quantite_"+id);
        var prix_unitaire = document.getElementById("prix_unitaire_"+id);
        var prix_total = document.getElementById("prix_total_"+id);

        // var q =qte.value;
        // var p =prix_unitaire.value;
        // var t = q*p ;
        // alert(""+q+"*"+p+"="+t);
        // alert(qte.value);
        var total = prix_unitaire.value*qte.value;
        prix_total.value =total;
        calcul_grand_total();
    }

    function calcul_grand_total(){
        var prix_total = document.getElementsByClassName("prix_total");
        var grand_total_input = document.getElementById("grand_total_input");

        var grand_total =0;
        for (var i=0;i<prix_total.length;i++){
            grand_total += prix_total[i].value * 1;
        }
        grand_total_input.value = grand_total;
        calcul_reste_a_payer();
    }

    function calcul_reste_a_payer(){
        var prix_total = document.getElementsByClassName("prix_total");
        var grand_total_input = document.getElementById("grand_total_input");
        var avance_percue_input = document.getElementById("avance_percue");
        var reste_a_payer_input = document.getElementById("reste_a_payer");

        var reste_a_payer = grand_total_input.value * 1 - avance_percue_input.value;
        if(reste_a_payer<0){ reste_a_payer=0;}
        reste_a_payer_input.value = reste_a_payer;
    }
</script>

</body>
</html>

