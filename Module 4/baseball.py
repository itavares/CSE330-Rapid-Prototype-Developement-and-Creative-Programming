"""

Program created for Module 4-CSE330

Objective is to parse information from file with regex and print players batting average



Ighor Tavares



Imports used:

re : for regex

math : to calculate average (round)

operator: to sort the dictionary by value

sys,os: to get the command line argument

"""



import re

import math

import operator

import sys, os





# check num of arguments/ usage message

get_argcs = len(sys.argv)



if get_argcs != 2:

    sys.exit("USAGE: {}   <filename> ".format(sys.argv[0]))



# get file name

filename = sys.argv[1]



list_of_players = []

new_player = {}

updated_players = {}



# function returns valid player name if it doesnt alread exist in the list ( a way to filter repeated names)

def check_for_player(checkName):

    for player_name in list_of_players:

        if player_name['name'] == checkName:

            return player_name

# function returns average bat given player hits and bats

def get_average(hits,bats):

    avg = round(float((hits/bats)),3)

    return avg



# read file and perform all the operations, from adding new player to list, getting average, and printing them in order

with open(filename,mode='r') as f:

# f = open(filename, "r")

    line = f.readline()

    while line:

        # ragex pat to retrieve name, bat and hits from text file.

        get_name_pat = '(?P<name>[\w\s]+)\sbatted\s(\d)[\w\s]+with\s(\d)'

        get_bats_pat = '([\w\s]+)\sbatted\s(?P<bats>\d)'

        get_hits_pat = '([\w\s]+)\sbatted\s(\d)[\w\s]+with\s(?P<hits>\d)'



        # regex match

        return_name = re.match(get_name_pat,line)

        return_bat = re.match(get_bats_pat,line)

        return_hits = re.match(get_hits_pat,line)



        # if all are valid (returned okay), then created new player based on regex information

        if return_name and return_bat and return_hits:

            # create a new player if it doesnt already exist in our list_of_players

            new_player = {'name':return_name.group('name'),'bats': int(return_bat.group('bats')),'hits': int(return_hits.group('hits'))}

            player_name = return_name.group('name')



            update_player = check_for_player(return_name.group('name'))

            if update_player is None:

                # create new player if it doesnt exist

                new_player = {'name':return_name.group('name'),'bats': int(return_bat.group('bats')),'hits': int(return_hits.group('hits'))}

                list_of_players.append(new_player);

            elif (update_player):

                # updates player , so for each player, everytime is matched again, it only updates their bats and hits value.

                number_of_bats = int(return_bat.group('bats'));

                update_player['bats'] += number_of_bats

                number_of_hits = int(return_hits.group('hits'));

                update_player['hits'] += number_of_hits



        line = f.readline()



        # calculate the averege for each player

    for player_avg in list_of_players:

        average_bat = get_average(player_avg['hits'],player_avg['bats'])

        updated_players[player_avg['name']] = average_bat





        # sort list of updated players , note reverse is needed here to show highest value first

    sorted_list_of_avg = sorted(updated_players.items(), key=operator.itemgetter(1) ,reverse=True)



    # print player name and its average batting

    for avg_player in sorted_list_of_avg:

        # print(avg_player) #debug message

        print("{0} : {1:0.3f}".format(avg_player[0], avg_player[1]))








