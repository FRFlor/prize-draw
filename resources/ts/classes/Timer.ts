const QUICKEST_WAIT_MS: number = 50;
const LONGEST_WAIT_MS: number = 500;

export function getWaitTime(position: number, total: number): number {
    const power = getInterpolationPower(total);
    return (LONGEST_WAIT_MS - QUICKEST_WAIT_MS) * Math.pow(position, power) / Math.pow(total, power) + QUICKEST_WAIT_MS;
}

function getInterpolationPower(total: number): number {
    if (total < 50) {
        return 2;
    }

    return 10;
}
