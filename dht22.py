import os
import Adafruit_DHT as dht

os.environ['PYTHON_EGG_CACHE'] = '/tmp'
DHT = 23

h,t = dht.read_retry(dht.DHT22, DHT)

print(t)
print(h)

