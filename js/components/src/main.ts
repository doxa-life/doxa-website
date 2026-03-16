import 'intl-tel-input/build/css/intlTelInput.css'
import './phone-input';
import './uupgs-list';

declare global {
    interface Window {
        uupgsData: {
            images_url: string;
            icons_url: string;
        };
        doxaData: {
            statistics: {
                total_with_prayer: number;
                total_with_full_prayer: number;
                total_with_committed_prayer: number;
                total_with_fully_committed_prayer: number;
                total_adopted: number;
            };
        };
    }
}

async function getPeopleGroupsStatistics() {

    const apiUrl =
        location.href.includes('doxa.test')
        ? 'http://uupg.doxa.test/wp-json/dt-public/disciple-tools-people-groups-api/v1/data/statistics'
        : 'https://uupg.doxa.life/wp-json/dt-public/disciple-tools-people-groups-api/v1/data/statistics';
        //: 'https://pray.doxa.life/api/people-groups/statistics';

    const response = await fetch(apiUrl, {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
        },
    });
    const data = await response.json();

    window.doxaData = window.doxaData || {};
    window.doxaData.statistics = {
        total_with_prayer: Number(data.total_with_prayer || 0),
        total_with_full_prayer: Number(data.total_with_full_prayer || 0),
        total_with_committed_prayer: Number(data.total_with_committed_prayer || 0),
        total_with_fully_committed_prayer: Number(data.total_with_fully_committed_prayer || 0),
        total_adopted: Number(data.total_adopted || 0),
    }

    const prayerCurrentStatus = document.getElementById('prayer-current-status');
    const prayerCurrentStatusPercentage = document.getElementById('prayer-current-status-percentage');
    if (prayerCurrentStatus && prayerCurrentStatusPercentage && window.doxaData.statistics && window.doxaData.statistics.total_with_full_prayer) {
        prayerCurrentStatus.textContent = window.doxaData.statistics.total_with_full_prayer.toString();
        prayerCurrentStatusPercentage.style.width = (window.doxaData.statistics.total_with_full_prayer / 2085 * 100).toString() + '%';
    }

    const adoptedCurrentStatus = document.getElementById('adopted-current-status');
    const adoptedCurrentStatusPercentage = document.getElementById('adopted-current-status-percentage');
    if (adoptedCurrentStatus && adoptedCurrentStatusPercentage && window.doxaData.statistics && window.doxaData.statistics.total_adopted) {
        adoptedCurrentStatus.textContent = window.doxaData.statistics.total_adopted.toString();
        adoptedCurrentStatusPercentage.style.width = (window.doxaData.statistics.total_adopted / 2085 * 100).toString() + '%';
    }
}

getPeopleGroupsStatistics();