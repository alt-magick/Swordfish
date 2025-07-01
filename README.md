# Swordfish
Graphical Web Terminal<br>

Drop the files in a web directory that is http (not https).<br>
You need to create a new folder in that directory falled "files".<br>
The deffault username/password is swordfish/swordfish.<br>
<br>
Once the website is live you need to setup ttyd:<br>
<br>
wget https://github.com/tsl0922/ttyd/releases/latest/download/ttyd.x86_64<br>
chmod +x ttyd.x86_64<br>
sudo mv ttyd.x86_64 /usr/local/bin/ttyd<br>
ttyd --version<br>
<br>
Once its installed run it in the background with screen:<br>
<br>
screen -S ttyd-session<br>
ttyd bash -c 'read -p "Username: " user; exec ssh $user@localhost'<br>
Ctrl + A then D<br>
<br>
In index.html change www.yourserver.com to your server<br>
<br>
Set permissions for the web server:<br>
<br>
sudo chown -R www-data:www-data /path/to/your/folder<br>
sudo find /path/to/your/folder -type d -exec chmod 775 {} \;<br>
sudo find /path/to/your/folder -type f -exec chmod 775 {} \;<br>
sudo chmod -R u+rwX /path/to/your/folder<br>
<br>

(Bug with symbolic link. Sub directory of program needs to be files/projects and has to link to /home/projects)
