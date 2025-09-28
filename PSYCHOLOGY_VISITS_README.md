# Psychology Visits Module

A comprehensive module for managing psychology appointments, sessions, and secure notes in the Smart Health Tracker application.

## Overview

The Psychology Visits module provides a complete solution for managing psychological services, including:

-   **Psychologist Management**: Register and manage psychologist profiles with specialties and availability
-   **Session Booking**: Book, reschedule, and manage therapy sessions
-   **Secure Notes**: Encrypted notes system for psychologists to maintain patient records
-   **Availability System**: Real-time availability checking and slot management
-   **Admin Interface**: Complete admin panel for managing psychologists

## Features

### 🧠 Psychologist Management

-   Complete psychologist profiles with specialties
-   Availability scheduling system
-   Hourly rate management
-   Active/inactive status control

### 📅 Session Management

-   Book sessions with available psychologists
-   Real-time availability checking
-   Session status tracking (booked, confirmed, completed, cancelled)
-   Rescheduling and cancellation with business rules
-   Session fee management

### 🔒 Secure Notes System

-   Encrypted notes for patient privacy
-   Multiple note types (session notes, assessments, follow-ups)
-   Access control (only psychologist who wrote the note can access)
-   Search and export functionality

### 🎯 Availability System

-   Weekly availability schedules
-   Real-time slot checking
-   Conflict prevention
-   Filtering by date, time, and specialty

## API Endpoints

### Psychologists

```
GET    /api/v1/psychologists                    # List psychologists with filters
POST   /api/v1/psychologists                   # Create psychologist
GET    /api/v1/psychologists/{id}              # Get psychologist details
PUT    /api/v1/psychologists/{id}              # Update psychologist
DELETE /api/v1/psychologists/{id}              # Delete psychologist
GET    /api/v1/psychologists/{id}/availability  # Get available slots
POST   /api/v1/psychologists/{id}/check-availability # Check specific time availability
```

### Sessions

```
GET    /api/v1/psy-sessions                    # List sessions with filters
POST   /api/v1/psy-sessions                    # Book new session
GET    /api/v1/psy-sessions/{id}               # Get session details
PUT    /api/v1/psy-sessions/{id}               # Update session
DELETE /api/v1/psy-sessions/{id}               # Delete session
POST   /api/v1/psy-sessions/{id}/cancel        # Cancel session
POST   /api/v1/psy-sessions/{id}/reschedule    # Reschedule session
POST   /api/v1/psy-sessions/{id}/complete      # Complete session
GET    /api/v1/psy-sessions/patient/{id}       # Get patient sessions
GET    /api/v1/psy-sessions/psychologist/{id}  # Get psychologist sessions
```

### Notes

```
GET    /api/v1/psy-sessions/{id}/notes         # Get session notes
POST   /api/v1/psy-sessions/{id}/notes        # Create note
GET    /api/v1/psy-sessions/{id}/notes/{noteId} # Get specific note
PUT    /api/v1/psy-sessions/{id}/notes/{noteId} # Update note
DELETE /api/v1/psy-sessions/{id}/notes/{noteId} # Delete note
GET    /api/v1/psychologists/{id}/notes       # Get all psychologist notes
GET    /api/v1/psychologists/{id}/notes/search # Search notes
GET    /api/v1/psychologists/{id}/notes/statistics # Get note statistics
GET    /api/v1/psychologists/{id}/notes/export # Export notes
```

## Query Parameters

### Psychologist Filters

-   `specialty`: Filter by specialty (e.g., anxiety, depression)
-   `date`: Show psychologists available on specific date (YYYY-MM-DD)
-   `time`: Filter by time slot (morning, afternoon, evening)
-   `with_availability`: Include available slots in response

### Session Filters

-   `psychologist_id`: Filter by psychologist
-   `patient_id`: Filter by patient
-   `status`: Filter by status (booked, confirmed, completed, etc.)
-   `start_date`: Filter sessions from this date
-   `end_date`: Filter sessions until this date
-   `upcoming`: Get only upcoming sessions
-   `past`: Get only past sessions

## Database Schema

### Psychologists Table

```sql
- id (primary key)
- name
- email (unique)
- phone
- specialty
- bio
- availability (JSON)
- hourly_rate
- is_active
- created_at, updated_at
```

### Psy Sessions Table

```sql
- id (primary key)
- psychologist_id (foreign key)
- patient_id (foreign key to users)
- start_time
- end_time
- status (enum: booked, confirmed, in_progress, completed, cancelled, no_show)
- notes (public notes)
- session_fee
- created_at, updated_at
```

### Psy Notes Table

```sql
- id (primary key)
- psy_session_id (foreign key)
- psychologist_id (foreign key)
- content (encrypted)
- note_type (enum: session_notes, assessment, follow_up, treatment_plan, progress_notes, other)
- is_encrypted (boolean)
- created_at, updated_at
```

## Business Rules

### Session Booking

-   Sessions must be scheduled for future dates
-   No double-booking allowed
-   Psychologist must be active and available
-   Default session duration is 1 hour

### Cancellation Policy

-   Sessions can be cancelled up to 24 hours before start time
-   Cancellation reason is recorded

### Rescheduling Policy

-   Sessions can be rescheduled up to 48 hours before start time
-   New time must be available

### Notes Security

-   Notes are encrypted by default
-   Only the psychologist who wrote the note can access it
-   Notes are linked to specific sessions

## Admin Interface

The module includes a complete admin interface accessible at `/admin/psychologists` with:

-   **List View**: View all psychologists with statistics
-   **Create Form**: Add new psychologists with availability scheduling
-   **Edit Form**: Update psychologist information
-   **Detail View**: View psychologist profile and recent sessions
-   **Delete Functionality**: Remove psychologists (with confirmation)

## Installation & Setup

1. **Run Migrations**:

    ```bash
    php artisan migrate
    ```

2. **Seed Sample Data**:

    ```bash
    php artisan db:seed --class=PsychologyVisitsSeeder
    ```

3. **Access Admin Panel**:
    - Login as admin user
    - Navigate to `/admin/psychologists`

## Usage Examples

### Booking a Session

```bash
curl -X POST http://localhost:8000/api/v1/psy-sessions \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "psychologist_id": 1,
    "patient_id": 1,
    "start_time": "2025-01-30 10:00:00",
    "notes": "Initial consultation"
  }'
```

### Getting Available Psychologists

```bash
curl -X GET "http://localhost:8000/api/v1/psychologists?specialty=anxiety&date=2025-01-30&with_availability=true" \
  -H "Authorization: Bearer YOUR_TOKEN"
```

### Adding a Secure Note

```bash
curl -X POST http://localhost:8000/api/v1/psy-sessions/1/notes \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "content": "Patient showed good progress in managing anxiety triggers.",
    "note_type": "session_notes"
  }'
```

## Security Features

-   **Encrypted Notes**: All sensitive notes are encrypted using Laravel's encryption
-   **Access Control**: Notes can only be accessed by the psychologist who wrote them
-   **Authentication**: All API endpoints require authentication
-   **Validation**: Comprehensive input validation on all endpoints
-   **Business Rules**: Enforced through service layer

## Testing

The module includes sample data for testing:

-   3 sample psychologists with different specialties
-   3 sample patients
-   4 sample sessions (past and future)
-   2 sample encrypted notes

## Future Enhancements

-   **Email Notifications**: Send confirmation emails for bookings
-   **Calendar Integration**: Sync with external calendar systems
-   **Payment Integration**: Handle session payments
-   **Video Conferencing**: Integrate with video call platforms
-   **Reporting**: Generate session and note reports
-   **Mobile App**: React Native or Flutter mobile app

## Support

For questions or issues with the Psychology Visits module, please refer to the main project documentation or contact the development team.

