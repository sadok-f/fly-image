#!/bin/bash

# Path to your parameters.yml file
YAML_FILE="/var/www/html/config/parameters.yml"

# Directory to clean up
TMP_DIR="/var/www/html/var/tmp"

# Extract the cron_interval value using grep and cut
CRON_INTERVAL=$(grep -E '^cronjob_cleanup_interval:' "$YAML_FILE" | cut -d' ' -f2- | tr -d '"')

# If the cron interval is empty or not found, then default every 2 hours
if [ -z "$CRON_INTERVAL" ]; then
  CRON_INTERVAL="0 */2 * * *"
fi

# Cleanup the tmp directory
find "$TMP_DIR" -type f -delete

# Update the cron job with the new interval
echo "$CRON_INTERVAL root /var/www/html/cleanup-tmp.sh" > /etc/cron.d/cleanup-cron

# Ensure cron job has the correct permissions
chmod 0644 /etc/cron.d/cleanup-cron

# Apply the cron job
cron
