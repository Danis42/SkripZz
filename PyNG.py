import argparse
import os
import threading
import sys
from subprocess import call

def start_client():
    call(["python", "chat_client.py" , "localhost", "1337"])

def start_server():
    call(["python", "chat_server.py"])

def main():
   parser = argparse.ArgumentParser(description=
            'Network games Server/Client',version="%prog 1.0")
  
   parser.add_argument("-l", "--list",
      action='store_true',
      #default="False",
      help="Lists all found Server")
   parser.add_argument("-c", "--connect",
      action="store",
      #default="[localhost,1337]",
      nargs=2,
      help="Connect to Server (default localhost 1337)",)
   parser.add_argument("-s", "--start",
      action="store",
      #default=1337,
      help="Start Server (default localhost 1337)",)
   args = parser.parse_args()

   if args.list != False:
      print "List all the servers"
   elif args.connect:
      print "connect to ", args.connect
      #os.system("chat_server.py 1")
      processThread = threading.Thread(target=start_server)  # <- note extra ','
      processThread.start()
   elif args.start:
      print "Start server"
      os.system("chat_client.py 1")
   else:
      print "wooops"

   print args

   text = raw_input("Join server ?")  # Python 2
   if text == "yes":
      processThread = threading.Thread(target=start_client)  # <- note extra ','
      processThread.start()
   else:
      print "wooops"
      
if __name__ == "__main__":
   main()