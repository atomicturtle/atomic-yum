%{?dist: %{expand: %%define %dist 1}}

Summary:        Atomic's Yum Web interface
Name:           atomic-yum
Version:        0.4
Release:        3
URL:            http://www.atomicorp.com/
Packager:	Scott R. Shinn <scott@atomicrocketturtle.com>
Vendor:		Atomic Corporate Industries
Source0:        %{name}-%{version}.tar.gz
Source1:        atomic-yum-button.gif
License:        GPLv3
Group:          System/Servers
BuildRoot:      %{_tmppath}/%{name}-%{version}-%{release}-root
BuildArch:      noarch
Requires:       psa sudo
%{?fc4:Requires: yum-plugin-repoheader }
%{?fc5:Requires: yum-plugin-repoheader }
%{?el4:Requires: yum-plugin-repoheader }
#%{?el5:Requires: yum-repolist }
#%{?fc6:Requires: yum-repolist }





%description
Atomic Yum is a web based interface to yum for Plesk Server Administrator.


%prep
	
%setup 


%build
find . -name .\*swp -exec rm -f {} \;
find . -name \*bak -exec rm -f {} \;
find . -name \*old -exec rm -f {} \;
find . -name .svn -exec rm -f {} \;

%install
%{__rm} -rf %{buildroot}
#%{__mkdir_p} %{buildroot}/usr/bin
%{__mkdir_p} %{buildroot}/usr/local/psa/admin/htdocs/yum
%{__mkdir_p} %{buildroot}/usr/local/psa/admin/htdocs/images/custom_buttons/

%{__cp} -r * %{buildroot}/usr/local/psa/admin/htdocs/yum
%{__install} %{SOURCE1} %{buildroot}/usr/local/psa/admin/htdocs/images/custom_buttons/atomic-yum-button.gif





%clean
%{__rm} -rf %{buildroot}

%post 

# Set up the button in PSA
MYSQL="mysql -u admin -p`cat /etc/psa/.psa.shadow` psa "
ATOMIC_VERSION=`echo "select text from custom_buttons where text = 'Atomic Yum Updater' " | $MYSQL`


if [ ! "$ATOMIC_VERSION" ]; then
  echo "insert into custom_buttons (sort_key,level,place,text,url,conhelp,options,file) values ('100','1','navigation','Atomic Yum Updater','/yum/index.php','Software Updater','256', 'atomic-yum-button.gif'); " | $MYSQL
fi


# Add yum to sudoers
if !  grep -q ^psaadm.*yum /etc/sudoers ; then
  echo "psaadm  ALL = NOPASSWD: /usr/bin/yum" >> /etc/sudoers
fi

# Comment out Defaults    requiretty
if egrep -q "^Defaults.*requiretty" /etc/sudoers; then
  perl -p -i -e "s/^Defaults.*requiretty/#Defaults    requiretty/" /etc/sudoers
fi




#%postun
# TODO: need to do cleanup


%files
%defattr(-,root,root)
%dir /usr/local/psa/admin/htdocs/yum/
%attr(0755,psaadm,psaadm) %dir /usr/local/psa/admin/htdocs/yum/global/3rd_party/smarty/templates_c/
/usr/local/psa/admin/htdocs/yum/
/usr/local/psa/admin/htdocs/images/custom_buttons/atomic-yum-button.gif



%changelog
* Tue Feb 5 2008 Scott R. Shinn <scott@atomicrocketturtle.com> - 0.4-1
- added in admin checks
- updated template and code for "single" column display mode (used on install/update/removes)

* Tue Jan 29 2008 Scott R. Shinn <scott@atomicrocketturtle.com> - 0.3-1
- Chris Hickman at www.antiochwebhost.com  updated the template for better plesk integration, and overall prettiness. 
- Berrie Pelser at www.ber-art.nl provided several new logos.
- Removed the restart event from %post


* Sat Jan 26 2008 Scott R. Shinn <scott@atomicrocketturtle.com> - 0.2-1
- update for centos 4 support

* Sat Jan 26 2008 Scott R. Shinn <scott@atomicrocketturtle.com> - 0.1-3
- path fix

* Fri Jan 25 2008 Scott R. Shinn <scott@atomicrocketturtle.com> - 0.1
- Tony at www.helixdevelopment.com rewrote the prototype to use classes, and added in smarty support

