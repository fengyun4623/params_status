from pprint import pprint
import json
import pdb
import urllib.request
from threading import Thread, Lock
import subprocess
import re
import time
from collections import OrderedDict
from queue import Queue
import os
import logging
import threading

global final_dict, team_id, max_thread, domain_buffer
final_dict = {}
max_thread = 240

team_id = { 
            #'JDI-REG-SERVICES' : 77,
            #'JDI-REG-BBE' : 78,
            #'JDI-REG-MMX' : 79,
            'JDI-REG-KM' : 80,
            #'JDI-REG-TPTX': 81,
            #'JDI-REG-RPD' : 82,
            #'JDI-REG-ACX' : 83,
            #'JDI-REG-EX' : 85,
            #'JDI-REG-LEGACY-EX' : 87,
            #'JDI-REG-LEGACY-QFX' : 88,
            #'JDI-REG-QFX' : 89
        }

def get_url_data(url):
    '''
    Get URL will return the data from URL requested.
    '''
    response = urllib.request.urlopen(url)
    content = response.read()
    data = json.loads(content.decode("utf8"))
    return data

def params_find(domain, profile_q):
    '''

    WORK HARDEERRRRRR.....  [ - , - ] just like a 8055

    '''
    while profile_q.qsize():
        try:
            profile = profile_q.get()
            kwargs = profile[1]
            print('Looking for next profile inside {}'.format(domain[1]))
            logging.info('Looking for next profile inside {}'.format(domain[1]))
            p_flag = True
            pp_flag = True
            script_id = kwargs['s_id']
            params = kwargs['p_n']
            p_params = kwargs['pp_n']
            loc = kwargs['p_l']
            is_robo = kwargs['i_r']
            team = domain[2]
            if is_robo:
                path = '/volume/regressions/toby/test-suites'
                os.environ['TOBY'] = '1'
            else:
                os.environ.pop('TOBY', None)
                path = '/volume/regressions/JUNOS/HEAD'  
            cmd = '/volume/labtools/bin/params-find'
            p_cmd = '{}/{}'.format(path+loc, params)
            out = subprocess.Popen([cmd, p_cmd], env=dict(os.environ, **{'DT_DOMAIN':domain[1]}), stdout=subprocess.PIPE, stderr=subprocess.STDOUT)
            stdout,stderr = out.communicate()
            for word in stdout.decode('utf-8').split('\n'):
                if re.findall(r'(Error|Constraint Failure)',word):
                    p_flag = False
                else:
                    continue
            if p_params:
                p_cmd = '{}/{}'.format(path+loc, p_params)
                out = subprocess.Popen([cmd, p_cmd], env=dict(os.environ, **{'DT_DOMAIN':domain[1]}), stdout=subprocess.PIPE, stderr=subprocess.STDOUT)
                stdout,stderr = out.communicate()
                for word in stdout.decode('utf-8').split('\n'):
                    if re.findall(r'(Error|Constraint Failure)',word):
                        pp_flag = False
                    else:
                        continue
            else:
                pp_flag = None
        except Exception as err:
            logging.exception('Exception Occured')
            print('Exception {}'.format(err))
            profile_q.put(profile)
            profile_q.task_done()
        param_result = OrderedDict([
            ('s_id',script_id),
            ('p_n', params),
            ('p_r', p_flag),
            ('pp_r',pp_flag),
            ('pp_n',p_params),
            ('p_l',loc),
            ('i_r',is_robo)
        ])
        #[ pp_params[ [pass], [fail] ], params[ [pass], [fail] ] ]
        lock = Lock()
        with lock:
            if team in list(final_dict.keys()) and domain[1] in list(final_dict[team].keys()):
                pass
            elif team in list(final_dict.keys()):
                final_dict[team][domain[1]] = [ [ [], [] ], [ [], [] ] ]
            else:
                final_dict[team] = {}
                final_dict[team][domain[1]] = [ [ [], [] ], [ [], [] ] ]
            if p_params:
                if pp_flag:
                    final_dict[team][domain[1]][0][0].append(param_result)
                else:
                    final_dict[team][domain[1]][0][1].append(param_result)
            else:
                if p_flag:
                    final_dict[team][domain[1]][1][0].append(param_result)
                else:
                    final_dict[team][domain[1]][1][1].append(param_result)
        #Notify profile done.
        profile_q.task_done()

def get_script_details(domain_q):
    '''

    HAHAHAHA... :) meaowww   [ ^ - ^ ]

    '''
    while domain_q.qsize():
        try:
            domain = domain_q.get()
            print('Threads Started for Domain : {}'.format(domain[1]))
            logging.info('Threads Started for Domain : {}'.format(domain[1]))
            team = domain[2]
            profile_q = Queue(maxsize=0)
            profile_url = 'http://inception.juniper.net/fusion/v2/core/script_profile/search.json?options=distinct,no_limit_count&limit=500&results=script_profile(id,name,script_id,is_active,is_default)&query=script_profile(is_active%20=%20true),resource_set(id%20={})&_dc=1539860541140'.format(domain[0])
            script_profiles = get_url_data(profile_url)
            if script_profiles['groups'][0]['num_results'] != 0:
                for profile in script_profiles['groups'][0]['results']:
                    profile_id = profile['id']
                    script_id = profile['script_id']
                    script_url = "https://inception.juniper.net/fusion/v2/core/script_profile/search.json?_dc=1533215499604&results=script(name,location)%2Cscript_profile%2Cscript_config_file%2Cscript_profile_env%2Cresource_set%2Ctopology_compatibility%2Cpreferred_params%2Cscript_profile_property%2Ctags&query=script_profile(id={}),script_profile(is_active%20=%20true)&page=1&start=0".format(profile_id)
                    script = get_url_data(script_url)
                    for data in script['groups'][0]['results']:
                        script_name = data['script']['name']
                        params = data['script_config_file'][0]['name']
                        params_loc = data['script_config_file'][0]['location']
                        try:
                            prefer_param = data['preferred_params'][0]['name']
                        except Exception:
                            prefer_param = ''
                        is_robo = True if re.search(r'.robot$', script_name, re.I) else False
                        kwargs = OrderedDict([
                            ('s_id',script_id),
                            ('p_n', params),
                            ('pp_n',prefer_param),
                            ('p_l',params_loc),
                            ('i_r',is_robo)
                        ])
                        profile_q.put((profile_id, kwargs))
            else:
                lock = Lock()
                with lock:
                    if team in list(final_dict.keys()):
                        final_dict[team][domain[1]] = []
                    else:
                        final_dict[team] = {}
                        final_dict[team][domain[1]] = []
            var = int(max_thread/domain_buffer)
            num_threads = min(var, profile_q.qsize())
            for i in range(num_threads):
                labor = Thread(target=params_find, name='labor : {}'.format(i), args=(domain,profile_q), daemon=True)
                labor.start()
            #Wait for all profiles under domain to finish.
            profile_q.join()
            #Write Data to JSON.
            dump_to_json(domain)
            #Notify task for domain done.
            domain_q.task_done()
        except Exception as err:
            logging.exception('Exception Occured')
            domain_q.put(domain)
            domain_q.task_done()

def get_team_domain():
    '''

    Ready...

    ON YOUR MARKS.

    GET  -  SET  -  GO  ->

    '''
    global final_dict
    domain_q = Queue(maxsize=0)
    for team, id_ in team_id.items():
        log_file = 'Logs/DBP_logs_{}.log'.format(time.strftime("%Y%m%d-%H%M%S"))
        logging.basicConfig(filename=log_file, filemode='w', format='%(asctime)s %(message)s' ,level=logging.INFO)
        print('Started Threads for Team : {}'.format(team))
        logging.info('Started Threads for Team : {}'.format(team))
        team_domain_url = 'http://inception.juniper.net/fusion/v2/core/resource_set/search.json?options=distinct,no_limit_count&limit=500&results=resource_set(id,name,is_active)&query=resource_set(set_type=Domain,%20name=*****,%20is_active=true),team(id={})&_dc=1539807022461'.format(id_)
        data = get_url_data(team_domain_url)
        for domain in data['groups'][0]['results']:
            domain_q.put((domain['id'],domain['name'],team))
        global domain_buffer
        domain_buffer = min(4, domain_q.qsize())
        for i in range(domain_buffer):
            worker = Thread(target=get_script_details, name=i+1, daemon=True, args=(domain_q,))
            worker.start()
        #Wait for all Domains to finish.
        domain_q.join()
        print('Completed all Threads for Team : {}'.format(team))
        logging.info('Completed all Threads for Team : {}'.format(team))

def dump_to_json(domain_tup):
    '''

    /-\ |_ |_ 's     \/\/ E |_ |_

                    T |-| /-\ T     E /\/ D S      \/\/ E |_ |_     ....  !

    '''
    team = domain_tup[2]
    domain = domain_tup[1]
    result = final_dict[team][domain]
    file_ = 'DBP_Database/{}/{}.json'.format(team, domain)
    group = []
    inner_group = []
    if result:
        #Creating Structure
        for index, type_ in enumerate(['prefered_params', 'params']):
            lower_inner = []
            for result_index, result_name in enumerate(['all_pass', 'all_fail']):
                result_d = OrderedDict([
                    ('id',result_index),
                    ('name',result_name),
                    (str(result_name),len(result[index][result_index])),
                    (str(result_name+"values"),result[index][result_index])
                ])
                lower_inner.append(result_d)
            inner_group.append(lower_inner)
            kwargs =  OrderedDict([
                ('id',index),
                ('name',type_),
                (str(type_),len(result[index][0]) + len(result[index][1])),
                (str(type_+'values'),inner_group[index])
            ])
            group.append(kwargs)
        pdb.set_trace()
        kwargs = OrderedDict([
            ('team',team),
            ('domain',domain),
            ('timestamp',time.strftime(frmt)),
            ('total_result',group[0][str(type_)] + group[1][str(type_)]),
            ('groups',group)
        ])
    else:
        kwargs = OrderedDict([
                ('team',team),
                ('domain',domain),
                ('timestamp',time.strftime(frmt)),
                ('total_result',0),
                ('groups',group)
        ])
    #Loading Data to Json files
    with open(file_, 'w') as fp:
        json.dump(kwargs, fp, indent=4)
    lock = Lock()
    with lock:
        del final_dict[team][domain]
    print('JSON created for Domain {}'.format(domain))

frmt='%Y-%m-%d %H:%M:%S'
print('Start Time : {}'.format(time.strftime(frmt)))
t1=time.strftime(frmt)
#get_team_domain()
#get_team_domain()
#for domain in my_data:
#    if domain[2] == 'JDI-REG-MMX':
#        get_script_details(domain)
q = Queue(maxsize=0)
###q.put((275,'rbu-reg-blr-agave', 'JDI-REG-MMX'))
q.put((1190,'rtb12','JDI-REG-KM'))
###q.put((2127,'rbu-reg-blr-chotu','JDI-REG-MMX'))
##q.put((1318,'jdi-reg-blrt-dcbg-rod-lavc3', 'JDI-REG-LEGACY-QFX'))
#q.put((1306, 'jdi-reg-blrt-Java_Platform_BR4', 'JDI-REG-LEGACY-EX'))
##q.put(('1857', 'jdi-reg-blrt-pinnacle_vc', 'JDI-REG-QFX'))
domain_buffer = 4
for i in range(min(4, q.qsize())):
        worker = Thread(target=get_script_details, name=i+1, daemon=True, args=(q,))
        worker.start()
##get_script_details(q)
q.join()
#dump_to_json()
print('All Thread Finished.')

print('End Time : {} {}'.format(t1,time.strftime(frmt)))

