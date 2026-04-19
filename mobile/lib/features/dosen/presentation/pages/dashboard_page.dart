import 'package:flutter/material.dart';
import 'package:mobile/shared/widgets/dosen/bottom_nav.dart';

class DosenDashboardPage extends StatefulWidget {
  const DosenDashboardPage({super.key});

  @override
  State<DosenDashboardPage> createState() => _DosenDashboardPageState();
}

class _DosenDashboardPageState extends State<DosenDashboardPage> {
  final _currentIndex = 0;

  void _onNavTapped(int index) {
    if (index == _currentIndex) return;

    switch (index) {
      case 0:
        Navigator.pushReplacementNamed(context, '/dosen/dashboard');
        break;
      case 1:
        Navigator.pushReplacementNamed(context, '/dosen/kelas');
        break;
      case 2:
        Navigator.pushReplacementNamed(context, '/dosen/profile');
        break;
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      bottomNavigationBar: BottomNav(
        currentIndex: _currentIndex,
        onTap: _onNavTapped,
      ),
      body: SingleChildScrollView(
        child: Column(
          children: [

            /// 🔵 HEADER
            Container(
              width: double.infinity,
              padding: const EdgeInsets.fromLTRB(20, 60, 20, 30),
              decoration: const BoxDecoration(
                gradient: LinearGradient(
                  colors: [Color(0xFF7C3AED), Color(0xFF4F46E5)],
                ),
                borderRadius: BorderRadius.only(
                  bottomLeft: Radius.circular(30),
                  bottomRight: Radius.circular(30),
                ),
              ),
              child: const Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text("Selamat datang,", style: TextStyle(color: Colors.white70)),
                  SizedBox(height: 5),
                  Text(
                    "Dr. Ahmad Wijaya",
                    style: TextStyle(
                      color: Colors.white,
                      fontSize: 22,
                      fontWeight: FontWeight.bold,
                    ),
                  ),
                  Text(
                    "NIDN: 0012345678",
                    style: TextStyle(color: Colors.white70),
                  ),
                ],
              ),
            ),

            const SizedBox(height: 20),

            Padding(
              padding: const EdgeInsets.symmetric(horizontal: 16),
              child: Column(
                children: [

                  /// 🔥 MENU
                  _menuCard(
                    icon: Icons.qr_code,
                    title: "Mulai Absensi",
                    subtitle: "Generate QR code untuk kelas",
                    color: Colors.purple,
                  ),

                  _menuCard(
                    icon: Icons.monitor,
                    title: "Monitoring Real-time",
                    subtitle: "Pantau absensi mahasiswa live",
                    color: Colors.blue,
                  ),

                  _menuCard(
                    icon: Icons.menu_book,
                    title: "Kelas Saya",
                    subtitle: "Kelola kelas dan pertemuan",
                    color: Colors.green,
                  ),

                  const SizedBox(height: 20),

                  /// 📊 STAT
                  Row(
                    children: [
                      Expanded(child: _statCard("Total Kelas", "2", Colors.purple)),
                      const SizedBox(width: 10),
                      Expanded(child: _statCard("Sesi Aktif", "0", Colors.green)),
                    ],
                  ),

                  const SizedBox(height: 20),

                  /// 📚 HEADER LIST
                  Row(
                    mainAxisAlignment: MainAxisAlignment.spaceBetween,
                    children: const [
                      Text(
                        "Kelas Saya",
                        style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
                      ),
                      Text(
                        "Lihat Semua →",
                        style: TextStyle(color: Colors.purple),
                      ),
                    ],
                  ),

                  const SizedBox(height: 10),

                  /// 📋 LIST KELAS
                  _kelasCard("Pemrograman Web", "TI-301 • Genap 2025/2026"),
                  _kelasCard("Jaringan Komputer", "TI-304 • Genap 2025/2026"),

                  const SizedBox(height: 80),
                ],
              ),
            ),
          ],
        ),
      ),
    );
  }

  /// 🔹 MENU CARD
  Widget _menuCard({
    required IconData icon,
    required String title,
    required String subtitle,
    required Color color,
  }) {
    return Container(
      margin: const EdgeInsets.only(bottom: 12),
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(20),
        boxShadow: [
          BoxShadow(
            blurRadius: 10,
            color: Colors.black.withValues(alpha: 0.05),
          )
        ],
      ),
      child: Row(
        children: [
          Container(
            padding: const EdgeInsets.all(14),
            decoration: BoxDecoration(
              color: color.withValues(alpha: 0.1),
              borderRadius: BorderRadius.circular(16),
            ),
            child: Icon(icon, color: color),
          ),
          const SizedBox(width: 12),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(title, style: const TextStyle(fontWeight: FontWeight.bold)),
                Text(subtitle),
              ],
            ),
          ),
          const Icon(Icons.arrow_forward, size: 16)
        ],
      ),
    );
  }

  /// 🔹 STAT CARD
  Widget _statCard(String title, String value, Color color) {
    return Container(
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(20),
      ),
      child: Column(
        children: [
          Icon(Icons.circle, color: color, size: 12),
          const SizedBox(height: 6),
          Text(value,
              style: const TextStyle(fontSize: 22, fontWeight: FontWeight.bold)),
          Text(title),
        ],
      ),
    );
  }

  /// 🔹 KELAS CARD
  Widget _kelasCard(String title, String subtitle) {
    return Container(
      margin: const EdgeInsets.only(bottom: 10),
      padding: const EdgeInsets.all(14),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(16),
      ),
      child: Row(
        children: [
          const Icon(Icons.group),
          const SizedBox(width: 10),
          Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Text(title, style: const TextStyle(fontWeight: FontWeight.bold)),
              Text(subtitle),
            ],
          )
        ],
      ),
    );
  }
}