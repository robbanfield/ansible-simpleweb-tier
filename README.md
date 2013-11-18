ansible-simpleweb-tier
======================

Requirements
------------

Ansible version:

Git installation

%ansible --version
ansible 1.4 (devel 009fdbf96a) last updated 2013/11/14 20:52:38 (GMT -700)
- 1.3.4 wasn't honouring host_key_checking flag properly in the local ansible.cfg


Launch simple tier
------------------

	$ ansible-playbook -i ./hosts/ 01.cloud.yml
	
To test it, find the IP address of the 'Load Balancer', either in AWS or by reviewing the 
console log, looking for a line similar to:


	NOTIFIED: [restart haproxy] *************************************************** 
	changed: [54.201.88.86]

Launch additional web server
----------------------------

	$ ansible-playbook -i ./hosts/ 02.addWebServer.yml -e '{"securityGroup": {"group_id": "add id" } }'

Note: this will also update all current servers with latest copy of the application. They 
will all be in sync. It is not yet added to the LoadBalancer.

Refresh LoadBalancer
--------------------
Only do this assuming the application is deployed to all servers.

	$ ansible-playbook -i ./hosts/ lbservers.yml

