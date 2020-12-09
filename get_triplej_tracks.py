import requests
import json
import csv


def parse_response(r):
    data = r.json()
    for item in data['items']: 
        play = {}
        artists = []
        artist_count = 0
        for artist in item['recording']['artists']:
            play['artist_'+str(artist_count)] = artist['name']
            artist_count = artist_count + 1
            artists.append(artist['name'])   
        #play['played_time'] = item['played_time']
        #play['title'] = item['recording']['title']
        output_string = [item['played_time'], item['recording']['title'], play.get('artist_0', ''),play.get('artist_1', ''),play.get('artist_2', ''),play.get('artist_3', ''),artist_count]
        #print output_string
        output_file  = open('triplej_plays.csv', "a", newline='',encoding='utf-8')
        with output_file:
            writer = csv.writer(output_file, quoting=csv.QUOTE_ALL)
            writer.writerow(output_string)

# csv headers
with open('triplej_plays.csv','w') as f:
    f.write("timestamp,song,artist1,artist2,artist3,artist4,artistcount\n")

# basic parameters
base_url = 'http://music.abcradio.net.au/api/v1/plays/search.json'
q_limit = '100'
q_page = '0'
q_station = 'triplej'    
q_from = '2020-01-01T00:00:00.000Z'
q_to = '2020-01-03T23:59:59.000Z'

# find how many total items we're getting
query = base_url + '?from=%s&limit=%s&offset=%s&page=0&station=triplej&to=%s' % (q_from, q_limit, 0, q_to)
r = requests.get(query)
total = r.json()['total']

# retrieve that many items
x = 0
while x < total:
    q_offset = x
    q_limit = 100 # max 100 items per page
    print("Completed ", q_offset)
    query = base_url + '?from=%s&limit=%s&offset=%s&page=0&station=triplej&to=%s' % (q_from, q_limit, q_offset, q_to)
    r = requests.get(query)
    parse_response(r)
    x = x + q_limit