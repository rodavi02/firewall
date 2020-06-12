#!/bin/bash
if [ -f /usr/local/bin/rules.json ];
then
    #Formateamos el archivo que nos pasa la aplicación para poder utilizarl$
    sed -i 's/},{/\n/g' /usr/local/bin/rules.json
    sed -i 's/{//g' /usr/local/bin/rules.json
    sed -i 's/}//g' /usr/local/bin/rules.json
    sed -i 's/\[//g' /usr/local/bin/rules.json
    sed -i 's/\]//g' /usr/local/bin/rules.json
    sed -i 's/\"//g' /usr/local/bin/rules.json
    sed -i 's/\\//g' /usr/local/bin/rules.json

    #Contamos las líneas que tiene el documento que nos pasan.
    lines=`cat /usr/local/bin/rules.json | wc -l`

    iptables -F
    iptables -F -t nat
    echo 1 > /proc/sys/net/ipv4/ip_forward
    #Introducimos reglas iptables a la tabla NAT básicas para el funcionamiento.
    iptables -t nat -A POSTROUTING -o enp0s3 -j MASQUERADE
    iptables -t nat -A PREROUTING -i enp0s3 -p tcp --dport 80 -j DNAT --to 192.168.3.2
    iptables -t nat -A PREROUTING -i enp0s3 -p tcp --dport 443 -j DNAT --to 192.168.3.2
    iptables -t nat -A PREROUTING -i enp0s3 -p tcp --dport 25 -j DNAT --to 192.168.3.2
    contador=1
    #Creamos un bucle para recorrer el archivo línea a línea.
    while ((lines+1 >= contador))
    do
		head -$contador /usr/local/bin/rules.json | tail -1 > /usr/local/bin/line.json

        #Almacenamos en cada variable el valor de exactamente el campo que queremos.
		action=`cut -d ":" -f 2 /usr/local/bin/line.json | cut -d "," -f 1`
		traffic=`cut -d ":" -f 3 /usr/local/bin/line.json | cut -d "," -f 1`
		int_in=`cut -d ":" -f 4 /usr/local/bin/line.json | cut -d "," -f 1`
		int_out=`cut -d ":" -f 5 /usr/local/bin/line.json | cut -d "," -f 1`
		source=`cut -d ":" -f 6 /usr/local/bin/line.json | cut -d "," -f 1`
		destination=`cut -d ":" -f 7 /usr/local/bin/line.json | cut -d "," -f 1`
		protocol=`cut -d ":" -f 8 /usr/local/bin/line.json | cut -d "," -f 1`
		sport=`cut -d ":" -f 9 /usr/local/bin/line.json | cut -d "," -f 1`
		dport=`cut -d ":" -f 10 /usr/local/bin/line.json | cut -d "," -f 1`
		target=`cut -d ":" -f 11 /usr/local/bin/line.json | cut -d "," -f 1`

        #Una vez hecho esto, se realiza una condición que coge toda la casuística posible.
		rule="iptables "
		if [ "$action" == "-P" ];
		then
		   	rule=$rule"$action $traffic $target"
		else
	        if [ "$int_in" == "" ];
	        then
                if [ "$int_out" == "" ];
                then
                    if [ "$source" == "" ];
                    then
                        if [ "$destination" == "" ];
                        then
                            if [ "$protocol" == "" ];
                            then
                               	echo "//////////////////////////////////////////////////////////////"
							    echo "////////////////////// Regla no válida ///////////////////////"
							    echo "//////////////////////////////////////////////////////////////"
                            else
                                if [ "$sport" == "" ];
                                then
                                    if [ "$dport" == "" ];
                                    then
                                        rule=$rule"$action $traffic -p $protocol -j $target"
                                	else
                                        rule=$rule"$action $traffic -p $protocol --dport $dport -j $target"
                                    fi
                                else
                                	if [ "$dport" == "" ];
                                	then
                                		rule=$rule"$action $traffic -p $protocol --sport $sport -j $target"
                                	else
                                		rule=$rule"$action $traffic -p $protocol --sport $sport --dport $dport -j $target"
                                	fi
                                fi
                            fi
                        else
                        	if [ "$protocol" == "" ];
                            then
                            	rule=$rule"$action $traffic -d $destination -j $target"
                            else
                            	if [ "$sport" == "" ];
                                then
                                    if [ "$dport" == "" ];
                                    then
                                        rule=$rule"$action $traffic -d $destination -p $protocol -j $target"
                                	else
                                        rule=$rule"$action $traffic -d $destination -p $protocol --dport $dport -j $target"
                                    fi
                                else
                                	if [ "$dport" == "" ];
                                	then
                                		rule=$rule"$action $traffic -d $destination -p $protocol --sport $sport -j $target"
                                	else
                                		rule=$rule"$action $traffic -d $destination -p $protocol --sport $sport --dport $dport -j $target"
                                	fi
                                fi
                            fi
                       	fi
                    else
                    	if [ "$destination" == "" ];
                        then
                        	if [ "$protocol" == "" ];
                            then
                            	rule=$rule"$action $traffic -s $source -j $target"
                            else
                            	if [ "$sport" == "" ];
                                then
                                    if [ "$dport" == "" ];
                                    then
                                        rule=$rule"$action $traffic -s $source -p $protocol -j $target"
                                	else
                                        rule=$rule"$action $traffic -s $source -p $protocol --dport $dport -j $target"
                                    fi
                                else
                                	if [ "$dport" == "" ];
                                	then
                                		rule=$rule"$action $traffic -s $source -p $protocol --sport $sport -j $target"
                                	else
                                		rule=$rule"$action $traffic -s $source -p $protocol --sport $sport --dport $dport -j $target"
                                	fi
                                fi
                            fi
                        else
                        	if [ "$protocol" == "" ];
                            then
                            	rule=$rule"$action $traffic -s $source -d $destination -j $target"
                            else
                            	if [ "$sport" == "" ];
                                then
                                    if [ "$dport" == "" ];
                                    then
                                        rule=$rule"$action $traffic -s $source -d $destination -p $protocol -j $target"
                                	else
                                        rule=$rule"$action $traffic -s $source -d $destination -p $protocol --dport $dport -j $target"
                                    fi
                                else
                                	if [ "$dport" == "" ];
                                	then
                                		rule=$rule"$action $traffic -s $source -d $destination -p $protocol --sport $sport -j $target"
                                	else
                                		rule=$rule"$action $traffic -s $source -d $destination -p $protocol --sport $sport --dport $dport -j $target"
                                	fi
                                fi
                            fi
                       	fi
                    fi
                else
                	if [ "$source" == "" ];
                    then
                        if [ "$destination" == "" ];
                        then
                            if [ "$protocol" == "" ];
                            then
                            	rule=$rule"$action $traffic -o $int_out -j $target"
                            else
                                if [ "$sport" == "" ];
                                then
                                    if [ "$dport" == "" ];
                                    then
                                        rule=$rule"$action $traffic -o $int_out -p $protocol -j $target"
                                	else
                                        rule=$rule"$action $traffic -o $int_out -p $protocol --dport $dport -j $target"
                                    fi
                                else
                                	if [ "$dport" == "" ];
                                	then
                                		rule=$rule"$action $traffic -o $int_out -p $protocol --sport $sport -j $target"
                                	else
                                		rule=$rule"$action $traffic -o $int_out -p $protocol --sport $sport --dport $dport -j $target"
                                	fi
                                fi
                            fi
                        else
                        	if [ "$protocol" == "" ];
                            then
                            	rule=$rule"$action $traffic -o $int_out -d $destination -j $target"
                            else
                            	if [ "$sport" == "" ];
                                then
                                    if [ "$dport" == "" ];
                                    then
                                        rule=$rule"$action $traffic -o $int_out -d $destination -p $protocol -j $target"
                                	else
                                        rule=$rule"$action $traffic -o $int_out -d $destination -p $protocol --dport $dport -j $target"
                                    fi
                                else
                                	if [ "$dport" == "" ];
                                	then
                                		rule=$rule"$action $traffic -o $int_out -d $destination -p $protocol --sport $sport -j $target"
                                	else
                                		rule=$rule"$action $traffic -o $int_out -d $destination -p $protocol --sport $sport --dport $dport -j $target"
                                	fi
                                fi
                            fi
                       	fi
                    else
                    	if [ "$destination" == "" ];
                        then
                        	if [ "$protocol" == "" ];
                            then
                            	rule=$rule"$action $traffic -o $int_out -s $source -j $target"
                            else
                            	if [ "$sport" == "" ];
                                then
                                    if [ "$dport" == "" ];
                                    then
                                        rule=$rule"$action $traffic -o $int_out -s $source -p $protocol -j $target"
                                	else
                                        rule=$rule"$action $traffic -o $int_out -s $source -p $protocol --dport $dport -j $target"
                                    fi
                                else
                                	if [ "$dport" == "" ];
                                	then
                                		rule=$rule"$action $traffic -o $int_out -s $source -p $protocol --sport $sport -j $target"
                                	else
                                		rule=$rule"$action $traffic -o $int_out -s $source -p $protocol --sport $sport --dport $dport -j $target"
                                	fi
                                fi
                            fi
                        else
                        	if [ "$protocol" == "" ];
                            then
                            	rule=$rule"$action $traffic -o $int_out -s $source -d $destination -j $target"
                            else
                            	if [ "$sport" == "" ];
                                then
                                    if [ "$dport" == "" ];
                                    then
                                        rule=$rule"$action $traffic -o $int_out -s $source -d $destination -p $protocol -j $target"
                                	else
                                        rule=$rule"$action $traffic -o $int_out -s $source -d $destination -p $protocol --dport $dport -j $target"
                                    fi
                                else
                                	if [ "$dport" == "" ];
                                	then
                                		rule=$rule"$action $traffic -o $int_out -s $source -d $destination -p $protocol --sport $sport -j $target"
                                	else
                                		rule=$rule"$action $traffic -o $int_out -s $source -d $destination -p $protocol --sport $sport --dport $dport -j $target"
                                	fi
                                fi
                            fi
                       	fi
                    fi
                fi
            else
            	if [ "$int_out" == "" ];
                then
                    if [ "$source" == "" ];
                    then
                        if [ "$destination" == "" ];
                        then
                            if [ "$protocol" == "" ];
                            then
                            	rule=$rule"$action $traffic -i $int_in -j $target"
                            else
                                if [ "$sport" == "" ];
                                then
                                    if [ "$dport" == "" ];
                                    then
                                        rule=$rule"$action $traffic -i $int_in -p $protocol -j $target"
                                	else
                                        rule=$rule"$action $traffic -i $int_in -p $protocol --dport $dport -j $target"
                                    fi
                                else
                                	if [ "$dport" == "" ];
                                	then
                                		rule=$rule"$action $traffic -i $int_in -p $protocol --sport $sport -j $target"
                                	else
                                		rule=$rule"$action $traffic -i $int_in -p $protocol --sport $sport --dport $dport -j $target"
                                	fi
                                fi
                            fi
                        else
                        	if [ "$protocol" == "" ];
                            then
                            	rule=$rule"$action $traffic -i $int_in -d $destination -j $target"
                            else
                            	if [ "$sport" == "" ];
                                then
                                    if [ "$dport" == "" ];
                                    then
                                        rule=$rule"$action $traffic -i $int_in -d $destination -p $protocol -j $target"
                                	else
                                        rule=$rule"$action $traffic -i $int_in -d $destination -p $protocol --dport $dport -j $target"
                                    fi
                                else
                                	if [ "$dport" == "" ];
                                	then
                                		rule=$rule"$action $traffic -i $int_in -d $destination -p $protocol --sport $sport -j $target"
                                	else
                                		rule=$rule"$action $traffic -i $int_in -d $destination -p $protocol --sport $sport --dport $dport -j $target"
                                	fi
                                fi
                            fi
                       	fi
                    else
                    	if [ "$destination" == "" ];
                        then
                        	if [ "$protocol" == "" ];
                            then
                            	rule=$rule"$action $traffic -i $int_in -s $source -j $target"
                            else
                            	if [ "$sport" == "" ];
                                then
                                    if [ "$dport" == "" ];
                                    then
                                        rule=$rule"$action $traffic -i $int_in -s $source -p $protocol -j $target"
                                	else
                                        rule=$rule"$action $traffic -i $int_in -s $source -p $protocol --dport $dport -j $target"
                                    fi
                                else
                                	if [ "$dport" == "" ];
                                	then
                                		rule=$rule"$action $traffic -i $int_in -s $source -p $protocol --sport $sport -j $target"
                                	else
                                		rule=$rule"$action $traffic -i $int_in -s $source -p $protocol --sport $sport --dport $dport -j $target"
                                	fi
                                fi
                            fi
                        else
                        	if [ "$protocol" == "" ];
                            then
                            	rule=$rule"$action $traffic -i $int_in -s $source -d $destination -j $target"
                            else
                            	if [ "$sport" == "" ];
                                then
                                    if [ "$dport" == "" ];
                                    then
                                        rule=$rule"$action $traffic -i $int_in -s $source -d $destination -p $protocol -j $target"
                                	else
                                        rule=$rule"$action $traffic -i $int_in -s $source -d $destination -p $protocol --dport $dport -j $target"
                                    fi
                                else
                                	if [ "$dport" == "" ];
                                	then
                                		rule=$rule"$action $traffic -i $int_in -s $source -d $destination -p $protocol --sport $sport -j $target"
                                	else
                                		rule=$rule"$action $traffic -i $int_in -s $source -d $destination -p $protocol --sport $sport --dport $dport -j $target"
                                	fi
                                fi
                            fi
                       	fi
                    fi
                else
                	if [ "$source" == "" ];
                    then
                        if [ "$destination" == "" ];
                        then
                            if [ "$protocol" == "" ];
                            then
                            	rule=$rule"$action $traffic -i $int_in -o $int_out -j $target"
                            else
                                if [ "$sport" == "" ];
                                then
                                    if [ "$dport" == "" ];
                                    then
                                        rule=$rule"$action $traffic -i $int_in -o $int_out -p $protocol -j $target"
                                	else
                                        rule=$rule"$action $traffic -i $int_in -o $int_out -p $protocol --dport $dport -j $target"
                                    fi
                                else
                                	if [ "$dport" == "" ];
                                	then
                                		rule=$rule"$action $traffic -i $int_in -o $int_out -p $protocol --sport $sport -j $target"
                                	else
                                		rule=$rule"$action $traffic -i $int_in -o $int_out -p $protocol --sport $sport --dport $dport -j $target"
                                	fi
                                fi
                            fi
                        else
                        	if [ "$protocol" == "" ];
                            then
                            	rule=$rule"$action $traffic -i $int_in -o $int_out -d $destination -j $target"
                            else
                            	if [ "$sport" == "" ];
                                then
                                    if [ "$dport" == "" ];
                                    then
                                        rule=$rule"$action $traffic -i $int_in -o $int_out -d $destination -p $protocol -j $target"
                                	else
                                        rule=$rule"$action $traffic -i $int_in -o $int_out -d $destination -p $protocol --dport $dport -j $target"
                                    fi
                                else
                                	if [ "$dport" == "" ];
                                	then
                                		rule=$rule"$action $traffic -i $int_in -o $int_out -d $destination -p $protocol --sport $sport -j $target"
                                	else
                                		rule=$rule"$action $traffic -i $int_in -o $int_out -d $destination -p $protocol --sport $sport --dport $dport -j $target"
                                	fi
                                fi
                            fi
                       	fi
                    else
                    	if [ "$destination" == "" ];
                        then
                        	if [ "$protocol" == "" ];
                            then
                            	rule=$rule"$action $traffic -i $int_in -o $int_out -s $source -j $target"
                            else
                            	if [ "$sport" == "" ];
                                then
                                    if [ "$dport" == "" ];
                                    then
                                        rule=$rule"$action $traffic -i $int_in -o $int_out -s $source -p $protocol -j $target"
                                	else
                                        rule=$rule"$action $traffic -i $int_in -o $int_out -s $source -p $protocol --dport $dport -j $target"
                                    fi
                                else
                                	if [ "$dport" == "" ];
                                	then
                                		rule=$rule"$action $traffic -i $int_in -o $int_out -s $source -p $protocol --sport $sport -j $target"
                                	else
                                		rule=$rule"$action $traffic -i $int_in -o $int_out -s $source -p $protocol --sport $sport --dport $dport -j $target"
                                	fi
                                fi
                            fi
                        else
                        	if [ "$protocol" == "" ];
                            then
                            	rule=$rule"$action $traffic -i $int_in -o $int_out -s $source -d $destination -j $target"
                            else
                            	if [ "$sport" == "" ];
                                then
                                    if [ "$dport" == "" ];
                                    then
                                        rule=$rule"$action $traffic -i $int_in -o $int_out -s $source -d $destination -p $protocol -j $target"
                                	else
                                        rule=$rule"$action $traffic -i $int_in -o $int_out -s $source -d $destination -p $protocol --dport $dport -j $target"
                                    fi
                                else
                                	if [ "$dport" == "" ];
                                	then
                                		rule=$rule"$action $traffic -i $int_in -o $int_out -s $source -d $destination -p $protocol --sport $sport -j $target"
                                	else
                                		rule=$rule"$action $traffic -i $int_in -o $int_out -s $source -d $destination -p $protocol --sport $sport --dport $dport -j $target"
                                	fi
                                fi
                            fi
                       	fi
                    fi
                fi
            fi
        fi
        $rule
        ((contador++))
    done
    #Terminado de introducir en el cortafuegos las reglas iptables de la aplicación, borramos los archivos utilizados.
    rm /usr/local/bin/line.json
    rm /usr/local/bin/rules.json
    #Guardamos las reglas ipptables para que se mantengan si se reinicia el servidor.
    iptables-save > /etc/iptables/rules.v4
    echo "//////////////////////////////////////////////////////////////"
	echo "//////////////// Programa Finalizado con éxito ///////////////"
	echo "//////////////////////////////////////////////////////////////"
else
    echo "//////////////////////////////////////////////////////////////"
    echo "/////////////// El archivo necesario no existe ///////////////"
    echo "//////////////////////////////////////////////////////////////"
fi
