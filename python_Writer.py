#Python file that self expands
#

print("Content of its own file: \n")

self=open("python_Writer.py",'r')
self_content=self.read()
self.close
print(self_content)

self=open("python_Writer.py",'w+')
self.write(self_content+"print('this line did not exist')")
self.close

for i in range(0,100000):
	if i%1==0:
		x=1

