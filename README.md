# Swordfish
Graphical Web Terminal<br>

Drop the files in a web directory that is http (not https).<br>
You need to create a new folder in that directory falled "files".<br>
The deffault password is "password".<br>
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
