<h1>i-manager-web</h1>
    <p>
    Es la api RESTFUL del proyecto i-manager. La api está escrita en <span style='color:blue;'>PHP</span> puro. 
    </p>
    <h1>Todas las urls segun el METODO HTTP</h1>
    <h2>GET</h2>
    <ul>
        <li><h5>Todos los clientes / un cliente con un id</h5></li>
            <ul>
                <li>http://localhost/i-manager-web/api/clientes</li>
            </ul>
        <li><h5>Todos los Supldiores / un suplidor con un id</h5></li>
            <ul>
                <li>http://localhost/i-manager-web/api/suplidores</li>
            </ul>
        <li><h5>Balance de los clientes / balance de un cliente con un id</h5></li>
            <ul>
                <li>http://localhost/i-manager-web/api/clientes_balance</li>
            </ul>   
        <li><h5>Transacciones cxc de los clientes / transacciones de un cliente en particular </h5></li><!-- Me falta que se pueda buscar una transaccion en particular-->
            <ul>
                <li>http://localhost/i-manager-web/api/clientes_cxc</li>
            </ul>
        <li><h5>Transacciones de los suplidores / transacciones de un suplidor / una transaccion en particular</h5></li>
            <ul>
                <li>http://localhost/i-manager-web/api/cxp</li>
            </ul>      
    </ul>