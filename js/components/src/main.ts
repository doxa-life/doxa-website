import 'intl-tel-input/build/css/intlTelInput.css'
import './phone-input';
import './uupgs-list';

declare global {
    interface Window {
        uupgsData: {
            images_url: string;
            icons_url: string;
            prayBaseUrl: string;
        };
        doxaData: {
            statistics: {
                total_with_prayer: number;
                total_with_full_prayer: number;
                total_adopted: number;
            };
        };
    }
}

async function getPeopleGroupsStatistics() {

    const prayBaseUrl = window.uupgsData?.prayBaseUrl || 'https://pray.doxa.life';
    const apiUrl = prayBaseUrl + '/api/people-groups/statistics';

    const response = await fetch(apiUrl);
    const data = await response.json();

    window.doxaData = window.doxaData || {};
    window.doxaData.statistics = {
        total_with_prayer: Number(data.total_with_prayer || 0),
        total_with_full_prayer: Number(data.total_with_full_prayer || 0),
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