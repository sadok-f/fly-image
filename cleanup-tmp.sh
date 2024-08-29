#!/bin/bash

set -e

# Path to your parameters.yml file
YAML_FILE="/var/www/html/config/parameters.yml"

# Directory to clean up
TMP_DIR="/var/www/html/var/tmp"

CRON_FILE="/etc/cron.d/cleanup-cron"

# Extract the cron_interval value using grep and cut
ENABLE_CLEANUP=$(grep -E '^enable_cronjob_cleanup:' "$YAML_FILE" | cut -d' ' -f2- | tr -d '"')
# Check if cleanup is disabled
if [ "$ENABLE_CLEANUP" == "false" ]; then
  echo "Cron job cleanup is disabled. Removing cron job."
  # Remove the cron job file
  if [ -f "$CRON_FILE" ]; then
    rm "$CRON_FILE"
    echo "Cron job removed."
  fi
  exit 0
fi

if [ ! -d "$TMP_DIR" ]; then
  echo "Directory $TMP_DIR does not exist. Exiting."
  exit 0
fi

CRON_INTERVAL=$(grep -E '^cronjob_cleanup_interval:' "$YAML_FILE" | cut -d' ' -f2- | tr -d '"')

# If the cron interval is empty or not found, then default every 5 hours
if [ -z "$CRON_INTERVAL" ]; then
  CRON_INTERVAL="0 */5 * * *"
fi

# Cleanup the tmp directory
find "$TMP_DIR" -type f -delete

# Update the cron job with the new interval
echo "$CRON_INTERVAL root /var/www/html/cleanup-tmp.sh" > "$CRON_FILE"

# Ensure cron job has the correct permissions
chmod 0644 "$CRON_FILE"

# Apply the cron job
cron -f
