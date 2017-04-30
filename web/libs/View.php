<?php

include_once $_SERVER["DOCUMENT_ROOT"] . "/web/clases/productReturner.php";

const CLIENT = 0;
const WAITER = 1;
const CHEF = 2;

class Comand{

    public $id;
    public $lines = [];
    public $charge = null;

    public function __construct($id, array $lines)
    {
        $this->id = $id;
        $this->lines = $lines;
    }


}

class TableComands{
    public $name;
    public $id;
    public $comand = [];

    public function __construct($table, $tableId, array $comand = [])
    {
        $this->name = $table;
        $this->comand = $comand;
        $this->id = $tableId;
    }


};

class View{
    public static function  header($title = 'Restaurante Pancracio',$cssPaths = ["assets/css/estilo.css"]){
        $header = "
<!DOCTYPE html>
<html lang=\"es\">
    <head>
        <title>$title</title>
        <meta charset=\"UTF-8\">
        <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
        <meta name=\"description\" content=\"Página web del restaurante Pancracio\">
        <meta name=\"description\" content=\"Pancracio, Restaurante, comida\">
        <meta name=\"author\" content=\"Jared Moises Santana Negrin & Alberto Casado Garfia\">
        <link rel=\"icon\" href=\"/assets/img/hamburguesa.jpg\">"
;

        foreach($cssPaths as $cssPath)
            $header.= "<link rel=\"stylesheet\" type=\"text/css\" href=$cssPath>";

        $header.=
        "</head>
         <body>
            <script src=\"https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js\"> </script>
            <div class=\"wrapper\">
                <header>
                    <h1>Restaurante Pancracio</h1>
                </header>
            <main>
         ";
        echo $header;
    }

    public static function menuWith($memuItems,$action){
        echo "<datalist id='articleList'>";
        foreach(returnProducts() as $article){
            echo "<option value='$article' />";
        };
        echo "</datalist>";
        $menu = "<nav>";
        foreach($memuItems as $text => $link)
            $menu .="<a class='menuItem' href=$link>$text</a>";
        $menu .= "<form action=$action method=\"POST\" style=\"float: right\">
                        <label style='color: white'>
                            Buscar
                        </label>
                        <input type=\"text\" list='articleList' name=\"searchDish\">
                        <input class=\"button\" type=\"submit\" id=\"searchIt\" value=\"Search\" name=\"searchIt\">
                    </form></nav>
        ";
        echo $menu;
    }


    public static function menuFor($userType,$index="",$tabla="",$contact="",$login="",$logout="",$comand="",$kitchen="",$action = ""){
        if(!isset($userType) || $userType == -1)
            self::menuWith(array("Inicio" => $index,
                "Platos" => $tabla,
                "Contacto" => $contact,
                "Entrar" => $login),$action);

        elseif( $userType == CLIENT )
            self::menuWith(array("Inicio" => $index,
                "Platos" => $tabla,
                "Contacto" => $contact,
                "Salir" => $logout),$action);

        elseif( $userType == WAITER )
            self::menuWith(array("Inicio" => $index,
                "Platos" => $tabla,
                "Contacto" => $contact,
                "Comandas" => $comand,
                "Salir" => $logout),$action);

        elseif( $userType == CHEF )
            self::menuWith(array("Inicio" => $index,
                "Platos" => $tabla,
                "Contacto" => $contact,
                "Cocina" => $kitchen,
                "Salir" => $logout),$action);
    }

    public static function index($h1 = 'Restaurante Pancracio'){
        $index = "
        	<article class=\"left\">
	            <img class=\"imgShowcase\" alt=\"platos cocinado por Pancracio\" src=\"assets/img/hamburguesa.jpg\"/>
	            <div class=\"content\">
	                <h2>Disfruta de nuestros platos</h2>
	                <p class=\"description\">
	                    Una gran variedad cocinada por Pancracio
	                    <a class=\"button\" href=\"web/clases/tabla.php\">Ver más platos...</a>
	                </p>
	            </div>
            </article>
            <article  class=\"right\">
	            <img class=\"imgShowcase\" alt=\"foto del restaurante\" src=\"assets/img/exterior.jpg\"/>
	            <div class=\"content\">
	                <h2>Y la mejor localización</h2>
	                <p class=\"description\">
	                    En el infinito y más alla. Y Tiene muchas mesas!
	                    <a class=\"button\" href=\"web/clases/contact.php\">Ver la localización exacta</a>
	                </p>
	            </div>
        	</article>
        ";
        echo $index;
    }

    public static function login($index){
        $login = "
    <article class=\"left\">
        <h1>Entrar:</h1>
        <form method=\"POST\" action=$index><label>
          Usuario: </label><input type=\"text\" name=\"username\"  /><br /><label>
          Contraseña: </label><input type=\"password\" name=\"password\" /><br />
          <div align=\"center\">
                <p><input class=\"button\" type=\"submit\" id= \"login\" name = \"login\" value=\"Entrar\" /></p>
          </div>
        </form>
    </article>";
        echo $login;
    }


    public static function contact(){
        $contactHtml = "
	    	<article>
	    		<!-- Si esto incumple la regla de enlaces relativos, pondría una imagen -->
	        	<iframe src=\"https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2960.3128017266895!2d-15.453368651201428!3d28.072574921688318!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0000000000000000%3A0x8b61a40c00405a46!2sEscuela+De+Ingenier%C3%ADa+Inform%C3%A1tica!5e0!3m2!1sen!2ses!4v1456065003715\"
	        		> </iframe>
	            <div class=\"content\">
	                <h2>Lugar</h2>
	                <address>
	                    En un lugar de la uni cuyo nombre no quiero acordarme
	                </address>
	            </div>

	    	</article>";
        echo $contactHtml;
    }

    public static function tabla(
        $title = ["Título del plato", "Tipo", "Época del año<br/> recomendada"],
        $rows = [["<a href=\"hamburguesa.html\">Hamburguesa con papas</a>", "Comida", "Toda"],
        ["<a href=\"tortilla.html\">Tortilla</a>","Comida","Toda"]]
    ){
        $tablaHtml = "<article>";

        $tablaHtml .= self::makeTable($title, $rows);
        $tablaHtml .= "</article>";
        echo $tablaHtml;
    }

    public static function kitchen($waitingComand, $cookingComand){
        echo self::kitchenScript();
        echo "<article><div class='content'>";

        echo "<h2>Comandas sin atender</h2>";
        $rows = [[]];
        foreach ($waitingComand as $row) {
            $orderHour = new DateTime("@" . $row['orderhour']);
            $orderHour = $orderHour->format('H:i');
            array_push($rows, [$row['order'], $row['article'], $orderHour, $row['waiter'],
                "<button onclick='update(\"/web/clases/kitchen.php\",\"" . $row['id'] . "\",\"cook\")'>Empezar elaboración</button>"
            ]);
        }

        echo View::makeTable(["Comanda", "Artículo", "Ordenado a las", "Por el camarero", "Opciones"], $rows);

        echo "</div><div class='content'><h2>Comandas que estas elaborando</h2>";
        $rows = [[]];
        foreach ($cookingComand as $row) {
            $cookStart = new DateTime("@" . $row['cookStartHour']);
            $cookStart = $cookStart->format('H:i');
            array_push($rows, [$row['order'], $row['article'], $cookStart, $row['table'], $row['waiter'],
                "<button onclick='update(\"/web/clases/kitchen.php\",\"" . $row['id'] . "\",\"endCook\")'>Terminar elaboración</button>"]);
        }



        echo View::makeTable(["Comanda", "Artículo", "Inicio de elaboración", "Mesa", "Camarero que atendio", "Opciones"], $rows);

        echo "</div></article>";
    }


    public static function kitchenScript(){
        $script = "
        <script src='assets/js/scripts.js'> </script>
        <script type='text/javascript'>
            var main = document.getElementsByTagName('main')[0];
            setInterval(function(){update('/web/clases/kitchen.php',-1,'onlyUpdate')}, 8000);

        </script>
        ";
        echo $script;
    }

    public static function waiterScript(){
        $script = "
        <script src='/assets/js/scripts.js'> </script>
        <script type='text/javascript'>
            var main;
            function addArticle(comand){
                var articles = '';
                times = Math.max(parseInt($('#productAmount' + comand).val()),1);
                var article = $('#product' + comand).val();
                for(var i = 0; i < times; i++){
                    articles += article + ',';
                }
                articles = articles.substr(0, articles.length -1);
                update('/web/clases/comand.php', comand, 'add', articles);

            }

            function onRefresh(){
                main = document.getElementsByTagName('main')[0];
                $('.comand').each(function() {
                    var totalPrice = 0;
                    $( this ).find('.foodTable').find('.price').each(function(){
                        totalPrice += parseFloat(this.innerHTML);
                    });
                    this.innerHTML += 'Coste actual: '+totalPrice.toFixed(2) + '€';
                });
                updateHiddenComands();
            }

            function updateHiddenComands(){
                var tables = document.getElementsByClassName('tableComand');

                for(var i = 0; i < tables.length; i++){
                    if(localStorage.getItem(tables.item(i).id) == null)
                            localStorage.setItem(tables.item(i).id, 'none');

                    var comands = tables.item(i).getElementsByClassName('comand');
                    var id = tables.item(i).id;
                    for(var j = 0; j < comands.length; j++)
                        comands.item(j).style.display = localStorage.getItem(id);

                };
            }

            function toggleVisibility(id){
                comands = document.querySelectorAll('#table'+id+' .comand');
                localStorage.setItem('table'+id, (localStorage.getItem('table'+id) == 'block')? 'none' : 'block');
                for(var i = 0; i < comands.length; i++){
                    $(comands.item(i)).slideToggle();
                }
            }

            $(window).ready(function () {
                onRefresh();
            });
        </script>
        ";
        echo $script;
    }

    public static function waiterComand($tableComands, $charge){
        self::waiterScript();

        echo '<article><button style="float:right" onclick=\'update("/web/clases/comand.php", "","refresh")\'>Refrescar</button>';

        if(!is_null($charge)){
            echo "<div class='content'>Comanda {$charge['id']}: A cobrar {$charge['charge']}</div>";
        }

        foreach ($tableComands as $table) {
            echo "<div class='content tableComand' id='table$table->id'><h1>$table->name</h1>";
            if( count($table->comand) > 0)
                echo "<button onclick='toggleVisibility($table->id)' style='display:inline'>Ver</button>";
            else
                echo "<button disabled style='display:inline'>Libre</button>";
            echo "<button onclick='update(\"/web/clases/comand.php\", \" $table->id \",\"addOrder\")'>Añadir Comanda</button>";
            foreach ($table->comand as $comand) {
                echo "<div class='comand' name='$comand->id' style='display:none'><h2>Comanda $comand->id </h2>";
                self::displayTableOrder($comand->lines, $comand->id);
                echo "</div>";

            }
            echo '</div>';
        }
        echo '</article>';
    }

    private static function displayTableOrder($order, $orderId)
    {
        $rows = [[]];
        foreach ($order as $row) {
            $status = self::OrderStatus($row);

            array_push($rows, [$row['articleName'], "<div class='price'>{$row['articlePrice']}</div>", $status,
                "<button onclick='update(\"/web/clases/comand.php\", \" {$row['id']} \",\"remove\")'>Quitar</button>" .
                ($status == 'A servir'? "
                    <button onclick='update(\"/web/clases/comand.php\", \" {$row['id']} \",\"serve\")'>Servir</button>" : '')
            ]);

        }

        echo self::makeTable(["Articulo", "Precio", "Estado", "Opciones"], $rows);

        echo "
            <input min='1' size='2' type='number' style='width:3em' id='productAmount$orderId' value='1'/>
            <input type='text' id='product$orderId' list='articleList'/>
            <button onclick='addArticle($orderId)'>Añadir</button>
        ";

        echo "<button onclick='update(\"/web/clases/comand.php\", \" $orderId \",\"charge\")'>Cobrar</button>";

    }

    private static function OrderStatus($queryRow)
    {
        $status = 'Servido';
        if ($queryRow['serveHour'] == 0) {
            if ($queryRow['articleType'] == 1) {
                if ($queryRow['cookEnd'] != 0)
                    $status = 'A servir';
                elseif ($queryRow['cookStart'] != 0)
                    $status = 'En cocina';
                else
                    $status = 'Pendiente';
            } else
                $status = 'A servir';
        }
        return $status;
    }

    public static function end($contact,$index= "../../index.php"){
        echo "
</main>
<footer>
        	Pancracio TM
        	<a href=$index>Página principal</a>
        	<a href=\"$contact\">Contacto</a>
        </footer>
    	</div>

    </body>
</html>";
    }

    public static function makeTable($title, $rows)
    {
        $tablaHtml = "<table class=\"foodTable\">
		            <tr>";
        foreach ($title as $title) {
            $tablaHtml .= "<th>" . $title . "</th>";
        };

        $tablaHtml .= "</tr>";
        foreach ($rows as $row) {
            $tablaHtml .= "<tr>";
            foreach ($row as $cell) {
                $tablaHtml .= "<td>" . $cell . "</td>";
            }
            $tablaHtml .= "</tr>";
        }

        $tablaHtml .= "</table>";
        return $tablaHtml;
    }
}
