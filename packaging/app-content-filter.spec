
Name: app-content-filter
Epoch: 1
Version: 2.3.0
Release: 1%{dist}
Summary: Content Filter Engine
License: GPLv3
Group: ClearOS/Apps
Source: %{name}-%{version}.tar.gz
Buildarch: noarch
Requires: %{name}-core = 1:%{version}-%{release}
Requires: app-base
Requires: app-antivirus
Requires: app-base >= 1:1.4.30
Requires: app-network
Requires: app-groups
Requires: app-web-proxy

%description
The Content Filter app allows an administrator to enforce Internet browsing policies.  Policies can be enforced across all users or user-specified groups.

%package core
Summary: Content Filter Engine - Core
License: LGPLv3
Group: ClearOS/Libraries
Requires: app-base-core
Requires: app-antivirus-core
Requires: app-base-core
Requires: app-events-core
Requires: app-firewall-core
Requires: app-policy-manager-core
Requires: app-groups-core >= 1:1.4.22
Requires: app-network-core
Requires: app-web-proxy-core
Requires: csplugin-filewatch
Requires: dansguardian-av >= 2.10.1.1-5

%description core
The Content Filter app allows an administrator to enforce Internet browsing policies.  Policies can be enforced across all users or user-specified groups.

This package provides the core API and libraries.

%prep
%setup -q
%build

%install
mkdir -p -m 755 %{buildroot}/usr/clearos/apps/content_filter
cp -r * %{buildroot}/usr/clearos/apps/content_filter/

install -d -m 0755 %{buildroot}/var/clearos/content_filter
install -d -m 0755 %{buildroot}/var/clearos/content_filter/backup/
install -D -m 0644 packaging/content_filter.acl %{buildroot}/var/clearos/base/access_control/public/content_filter
install -D -m 0644 packaging/content_filter.conf %{buildroot}/etc/clearos/content_filter.conf
install -D -m 0644 packaging/dansguardian-av.php %{buildroot}/var/clearos/base/daemon/dansguardian-av.php
install -D -m 0755 packaging/network-configuration-event %{buildroot}/var/clearos/events/network_configuration/content_filter

%post
logger -p local6.notice -t installer 'app-content-filter - installing'

%post core
logger -p local6.notice -t installer 'app-content-filter-core - installing'

if [ $1 -eq 1 ]; then
    [ -x /usr/clearos/apps/content_filter/deploy/install ] && /usr/clearos/apps/content_filter/deploy/install
fi

[ -x /usr/clearos/apps/content_filter/deploy/upgrade ] && /usr/clearos/apps/content_filter/deploy/upgrade

exit 0

%preun
if [ $1 -eq 0 ]; then
    logger -p local6.notice -t installer 'app-content-filter - uninstalling'
fi

%preun core
if [ $1 -eq 0 ]; then
    logger -p local6.notice -t installer 'app-content-filter-core - uninstalling'
    [ -x /usr/clearos/apps/content_filter/deploy/uninstall ] && /usr/clearos/apps/content_filter/deploy/uninstall
fi

exit 0

%files
%defattr(-,root,root)
/usr/clearos/apps/content_filter/controllers
/usr/clearos/apps/content_filter/htdocs
/usr/clearos/apps/content_filter/views

%files core
%defattr(-,root,root)
%exclude /usr/clearos/apps/content_filter/packaging
%dir /usr/clearos/apps/content_filter
%dir /var/clearos/content_filter
%dir /var/clearos/content_filter/backup/
/usr/clearos/apps/content_filter/deploy
/usr/clearos/apps/content_filter/language
/usr/clearos/apps/content_filter/libraries
/var/clearos/base/access_control/public/content_filter
%config(noreplace) /etc/clearos/content_filter.conf
/var/clearos/base/daemon/dansguardian-av.php
/var/clearos/events/network_configuration/content_filter
