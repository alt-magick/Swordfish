# Swordfish
Graphical Web Terminal

Drop the files in a web directory that is http (not https).
You need to create a new folder in that directory falled "files".
The deffault password is "password".

Once the website is live you need to setup ttyd:

wget https://github.com/tsl0922/ttyd/releases/latest/download/ttyd.x86_64
chmod +x ttyd.x86_64
sudo mv ttyd.x86_64 /usr/local/bin/ttyd
ttyd --version

Once its installed run it in the background with screen:

screen -S ttyd-session
ttyd bash -c 'read -p "Username: " user; exec ssh $user@localhost'
Ctrl + A then D

