#/bin/bash 

{% for host in groups['tag_ansible_group_dbservers'] %}
/usr/bin/mysql -u {{ dbuser}} --password={{upassword}} -h {{ hostvars[host].ec2_private_ip_address }} {{ dbname }} < /tmp/schema.sql
{% endfor %}